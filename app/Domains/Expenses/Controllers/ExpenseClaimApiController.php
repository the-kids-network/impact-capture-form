<?php

namespace App\Domains\Expenses\Controllers;

use App\Domains\Expenses\Models\ExpenseClaim;
use App\Domains\Expenses\Models\ExpenseClaimSearch;
use App\Domains\Expenses\Services\ExpenseClaimService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseClaimApiController extends Controller {

    private ExpenseClaimService $expenseClaimService;

    public function __construct(ExpenseClaimService $expenseClaimService) {
        $this->expenseClaimService = $expenseClaimService;
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get');
    }

    public function get(Request $request) {
        // Run search
        $search = (new ExpenseClaimSearch())->sessionId($request->session_id);

        $claims = $this->expenseClaimService->getExpenseClaimsUsing($search);

        // Construct response as JSON
        $payload = $claims->map(function($claim) use($request) {
            $claimDto = $this->mapClaimToDto($claim);

            if ($request->exclude_fields) {
                foreach ($request->exclude_fields as $fieldToExlude) {
                    unset($claimDto[$fieldToExlude]); 
                }
            }

            return $claimDto;
        });

        // return
        return response()->json($payload);
    }

    private function mapClaimToDto(ExpenseClaim $claim) {
        return [
            'id' => $claim->id,
            'mentor' => [
                'id' => $claim->mentor->id
            ],
            'session' => [
                'id' => $claim->report_id
            ],
            'status' => $claim->status,
            'finance_code' => $claim->check_number,
            'created_at' => $claim->created_at,
            'processing' =>  $claim->processedBy ? [
                'by' => [
                    'id' => $claim->processedBy->id,
                ],
                'at' =>$claim->processed_at
            ] : null,
            'amount_total' => round($claim->expenses->sum('amount'), 2),
            'expense_items' => $claim->expenses->map(fn ($item) => [
                'id' => $item->id,
                'date' => $item->date,
                'description' => $item->description,
                'amount' => $item->amount,
            ])
        ];
    }

}
