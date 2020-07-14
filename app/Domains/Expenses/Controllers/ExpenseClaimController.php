<?php

namespace App\Domains\Expenses\Controllers;

use App\Domains\Expenses\Models\ExpenseClaim;
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
        $this->middleware('admin')->only('update');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('index','export', 'show');
        $this->middleware('mentor')->only('newExpenseClaim', 'store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $query = ExpenseClaim::canSee()->orderBy('created_at','desc');

        if ($request->mentor_id) {
            $query->whereMentorId($request->mentor_id);
        }

        $expenseClaims = $query->get();

        return view('expense_claims.index')->with('expense_claims', $expenseClaims);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        if (!ExpenseClaim::find($id)) abort(404);

        $expense_claim = ExpenseClaim::canSee()->whereId($id)->first();
        
        if (!$expense_claim) abort(401,'Unauthorized');

        return view('expense_claims.show')->with('expense_claim', $expense_claim);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $request->validate([
            'status' => 'required'
        ]);

        $claim = $this->expenseClaimService->getClaim($id);
        $report = $this->sessionReportService->getReport($claim->report_id);
        $sessionDetails = [
            'mentee_name' => $report->mentee->name,
            'session_date' => $report->session_date
        ];

        if ($request->status == 'processed'){
            $this->expenseClaimService->processClaim($id, Auth::user(), $sessionDetails, $request->check_number);
            return redirect('/expense-claim/'.$id)->with('status','Expense Claim Processed');
        }

        if ($request->status == 'rejected'){
            $this->expenseClaimService->rejectClaim($id, Auth::user(), $sessionDetails);            
            return redirect('/expense-claim/'.$id)->with('status','Expense Claim Rejected');
        }
    }

    public function export(Request $request){
        $query = ExpenseClaim::canSee()->orderBy('created_at','desc');

        if ($request->mentor_id) {
            $query->whereMentorId($request->mentor_id);
        }

        $claims = $query->get();

        return view('expense_claims.export')->with('expense_claims', $claims);
    }

}
