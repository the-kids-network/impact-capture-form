<?php

namespace App\Domains\Expenses\Controllers;

use App\Domains\Expenses\Services\ExpenseClaimService;
use App\Domains\SessionReports\Services\SessionReportService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseClaimController extends Controller {

    private SessionReportService $sessionReportService;
    private ExpenseClaimService $expenseClaimService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SessionReportService $sessionReportService,
                                ExpenseClaimService $expenseClaimService) {
        $this->sessionReportService = $sessionReportService;
        $this->expenseClaimService = $expenseClaimService;

        $this->middleware('auth');
        $this->middleware('mentor')->only('newExpenseClaim', 'store');
    }

    /**
     *
     * Show the Expense Claim Form
     * @param Request $request
     * @return $this
     */
    public function newExpenseClaim(Request $request) {
        $reports = $this->sessionReportService->getReports();
        return view('expense_claims.new')
            ->with('reports', $reports)
            ->with('claims', $request->user()->expense_claims()->orderBy('created_at','desc')->get() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'expenses.*.date' => 'required|date|before_or_equal:today',
            'expenses.*.amount' => 'required|numeric',
            'expenses.*.description' => 'required',
            'receipts.*' => 'mimes:jpeg,png,gif,pdf,jpg|max:5000'
        ], [
            'expenses.*.date.before_or_equal' => "Expense item date should be before or equal to today"
        ]);

        // Validate user can actually save expense against session
        $report = $this->sessionReportService->getReport($request->report_id);
        if (!$report) abort(401,'Unauthorized');
        
        $sessionDetails = [
            'id' => $report->id,
            'mentee_name' => $report->mentee->name,
            'session_date' => $report->session_date
        ];

        // create claim
        $claim = $this->expenseClaimService->createClaim(
            Auth::user(),
            $sessionDetails,
            $request->expenses,
            $request->receipts
        );

        return redirect('/app#/expenses/'.$claim->id)->with('status','Expense Claim Submitted for Processing');
    }

}
