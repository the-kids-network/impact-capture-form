<?php

namespace App\Domains\Expenses\Services;

use App\Domains\Expenses\Mail\ClaimProcessedToMentor;
use App\Domains\Expenses\Mail\ClaimRejectedToMentor;
use App\Domains\Expenses\Mail\ClaimSubmittedToMentor;
use App\Domains\Expenses\Models\Expense;
use App\Domains\Expenses\Models\ExpenseClaim;
use App\Domains\Expenses\Models\ExpenseClaimSearch;
use App\Domains\Expenses\Models\Receipt;
use App\Exceptions\NotAuthorisedException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\NotFoundException;

class ExpenseClaimService {

    public function getExpenseClaimsUsing(ExpenseClaimSearch $search) {
        $query = ExpenseClaim::canSee()->with(['mentor', 'expenses', 'receipts', 'processedBy']);
        
        if ($search->sessionId) {
            $query->whereReportId($search->sessionId);
        }

        if ($search->mentorId) {
            $query->whereMentorId($search->mentorId);
        }

        if ($search->processedById) {
            $query->whereProcessedById($search->processedById);
        }

        if ($search->status) {
            $query->whereIn('status', $search->status);
        }
        
        if ($search->createdDateRangeStart) {
            $query->where('created_at', '>=', Carbon::createFromFormat('d-m-Y', $search->createdDateRangeStart)->setTime(0,0,0));
        }

        if ($search->createdDateRangeEnd) {
            $query->where('created_at', '<=', Carbon::createFromFormat('d-m-Y', $search->createdDateRangeEnd)->setTime(23,59,59));
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getClaim($id) {
        if (!ExpenseClaim::find($id)) throw new NotFoundException("Claim with ID not found");
        
        $claim = ExpenseClaim::canSee()->with(['mentor', 'expenses', 'receipts', 'processedBy'])
                                    ->whereId($id)
                                    ->first();

        if(!$claim) throw new NotAuthorisedException("Current user cannot get claim with ID");
        
        return $claim;
    }

    public function createClaim($mentor, $session, $expenseItems, $receipts) {
        // claim
        $claim = new ExpenseClaim();
        $claim->report_id = $session['id']; 
        $claim->mentor_id = $mentor->id;
        
        // expense items
        $expenseItems = collect($expenseItems)->map(fn($expenseItem) => 
            new Expense([
                'expense_claim_id' => $claim->id,
                'date' => Carbon::createFromFormat('d-m-Y',$expenseItem['date'])->setTime(0,0,0),
                'description' => $expenseItem['description'],
                'amount' => $expenseItem['amount']
            ])
        );
      
        // receipts
        $receipts = collect($receipts)->map(fn($receipt) => 
            new Receipt([
                'expense_claim_id' => $claim->id,
                'path' => $receipt->store('receipts')
            ])
        );

        // save atomically
        DB::transaction(function() use ($claim, $expenseItems, $receipts) {
            $claim->save();
            $claim->receipts()->saveMany($receipts);
            $claim->expenses()->saveMany($expenseItems);
        }); 

        // send an email to the mentor
        Mail::to($mentor)->send(new ClaimSubmittedToMentor($claim, $session));

        return $claim;
    }

    public function processClaim($claimId, $processedByUser, $session, $financeCode=null) {
        if (!ExpenseClaim::find($claimId)) throw new NotFoundException("Claim with ID not found");
        $claim = ExpenseClaim::canSee()->with(['mentor', 'expenses', 'receipts', 'processedBy'])->whereId($claimId)->first();
        if(!$claim) throw new NotAuthorisedException("Current user cannot get claim with ID");

        $claim->processedBy()->associate($processedByUser);
        $claim->processed_at = Carbon::now();
        $claim->status = 'processed';
        $claim->check_number = $financeCode;
        $claim->save();

        Mail::to($claim->mentor)->send(new ClaimProcessedToMentor($claim, $session));
        
        return $claim;
    }

    public function rejectClaim($claimId, $processedByUser, $session) {
        if (!ExpenseClaim::find($claimId)) throw new NotFoundException("Claim with ID not found");
        $claim = ExpenseClaim::canSee()->with(['mentor', 'expenses', 'receipts', 'processedBy'])->whereId($claimId)->first();
        if(!$claim) throw new NotAuthorisedException("Current user cannot get claim with ID");

        $claim->processedBy()->associate($processedByUser);
        $claim->processed_at = Carbon::now();
        $claim->status = 'rejected';
        $claim->save();

        Mail::to($claim->mentor)->send(new ClaimRejectedToMentor($claim));

        return $claim;
    }

    public function reverseClaimStatus($claimId) {
        if (!ExpenseClaim::find($claimId)) throw new NotFoundException("Claim with ID not found");
        $claim = ExpenseClaim::canSee()->with(['mentor', 'expenses', 'receipts', 'processedBy'])->whereId($claimId)->first();
        if(!$claim) throw new NotAuthorisedException("Current user cannot get claim with ID");

        $claim->processedBy()->dissociate();
        $claim->processed_at = null;
        $claim->status = 'pending';
        $claim->check_number = null;
        $claim->save();

        return $claim;
    }
}
