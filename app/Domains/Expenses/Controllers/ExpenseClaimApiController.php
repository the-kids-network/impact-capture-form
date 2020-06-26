<?php

namespace App\Domains\Expenses\Controllers;

use App\Domains\Expenses\Models\ExpenseClaim;
use App\Domains\Expenses\Models\ExpenseClaimSearch;
use App\Domains\Expenses\Services\ExpenseClaimService;
use App\Domains\SessionReports\Services\SessionReportService;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use League\Csv\Writer;

class ExpenseClaimApiController extends Controller {

    private ExpenseClaimService $expenseClaimService;
    private SessionReportService $sessionReportService;

    public function __construct(ExpenseClaimService $expenseClaimService,
                                SessionReportService $sessionReportService) {
        $this->expenseClaimService = $expenseClaimService;
        $this->sessionReportService = $sessionReportService;
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin')->only('updateClaimStatus');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get', 'getById', 'export');
    }

    public function get(Request $request) {
        // validate    
        $validator = $this->queryValidator($request->all());    
        if ($validator->fails()) {
            return $this->handleError($validator);
        }

        // Run search
        $search = $this->buildSearchParameters($request);
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

    public function getById($id) {
        $claim = null;
        try {
            $claim = $this->expenseClaimService->getClaim($id);
        } catch (NotFoundException | NotAuthorisedException $e) {
            Log::error($e);
            abort(401,'Unauthorized');
        }

        return response()->json($this->mapClaimToDto($claim));
    }

    public function export(Request $request) {
        // Validate    
        $validator = $this->queryValidator($request->all());    
        if ($validator->fails()) {
            return $this->handleError($validator);
        }

        // Run search
        $search = $this->buildSearchParameters($request);
        $claims = $this->expenseClaimService->getExpenseClaimsUsing($search);

        // Flatten expense items for CSV
        $flattenedData = $claims->flatMap(fn($claim) => 
            $claim->expenses->map(fn($expenseItem) => 
                [
                    'claim_id' => $claim->id,
                    'created_at' => $claim->created_at->format('d-m-Y'),
                    'session_id' => $claim->report_id,
                    'mentor' => $claim->mentor->name,
                    'status' => $claim->status,
                    'expense_item_date' => $expenseItem->date->format('d-m-Y'),
                    'expense_item_desc' => $expenseItem->description,
                    'expense_item_amount' => $expenseItem->amount,
                ]
            )
        )->toArray();

        // Build CSV
        $csv = Writer::createFromString('');
        $csv->insertOne([
            'Claim ID', 'Claim Created On', 'Claim Report (Session) ID', 'Mentor Name', 'Claim Status', 
            'Expense Item Date', 'Expense Item Description', 'Expense Item Amount'
        ]);
        $csv->insertAll($flattenedData);
        $csv->output('expenses.csv');       
    }

    public function updateClaimStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:rejected,processed,pending',
        ]);

        $claim = ExpenseClaim::canSee()->whereId($id)->first();
        $report = $this->sessionReportService->getReport($claim->report_id);
        $sessionDetails = [
            'mentee_name' => $report->mentee->name,
            'session_date' => $report->session_date
        ];

        $updatedClaim = null;
        if ($request->status == 'rejected')
            $updatedClaim = $this->expenseClaimService->rejectClaim($id, Auth::user(), $sessionDetails); 
        else if ($request->status == 'processed')
            $updatedClaim = $this->expenseClaimService->processClaim($id, Auth::user(), $sessionDetails, $request->finance_code);
        else if ($request->status == 'pending')
            $updatedClaim = $this->expenseClaimService->reverseClaimStatus($id);

        return response()->json($this->mapClaimToDto($updatedClaim));
    }

    private function buildSearchParameters(Request $request) {
        return (new ExpenseClaimSearch())
            ->sessionId($request->session_id)
            ->mentorId($request->mentor_id)
            ->processedById($request->processed_by_id)
            ->status($request->status)
            ->createdDateRangeStart($request->created_date_range_start)
            ->createdDateRangeEnd($request->created_date_range_end);
    }

    private function mapClaimToDto(ExpenseClaim $claim) {
        return [
            'id' => $claim->id,
            'mentor' => [
                'id' => $claim->mentor->id,
                'name' => $claim->mentor->name
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
                    'name' => $claim->processedBy->name
                ],
                'at' =>$claim->processed_at
            ] : null,
            'amount_total' => round($claim->expenses->sum('amount'), 2),
            'expense_items' => $claim->expenses->map(fn ($item) => [
                'id' => $item->id,
                'date' => $item->date,
                'description' => $item->description,
                'amount' => $item->amount,
            ]),
            'receipts' => $claim->receipts->map(fn ($item) => [
                "id" => $item->id
            ])
        ];
    }

    private function queryValidator($params) {
        return Validator::make(
            $params,  
            [
                'created_date_range_end' => 'date|after_or_equal:created_date_range_start',
            ], 
            [
                'created_date_range_end.after_or_equal' => 'The end date should be after or equal to the start date',
            ]
        );  
    }

}
