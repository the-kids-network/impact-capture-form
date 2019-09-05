<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseClaim;
use App\Mail\ClaimProcessedToMentor;
use App\Mail\ClaimRejectedToMentor;
use App\Mail\ClaimSubmittedToMentor;
use App\Receipt;
use App\Report;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ExpenseClaimController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin')->only('update');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('index','export', 'show');
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

        return view('expense_claim.index')->with('expense_claims', $expenseClaims);
    }

    /**
     *
     * Show the Expense Claim Form
     * @param Request $request
     * @return $this
     */
    public function newExpenseClaim(Request $request) {
        return view('expense_claim.new')
            ->with('reports', $request->user()->reports()->orderBy('created_at','desc')->get() )
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

        return view('expense_claim.show')->with('expense_claim', $expense_claim);
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
        ]);

        // Validate user can actually save expense against session
        $report = Report::canSee()->whereId($request->report_id)->first();
        if (!$report) {
            abort(401,'Unauthorized');
        }

        // Save expense claim
        $claim = $this->saveExpense($request);

        // Send an Email to the Mentor
        Mail::to($request->user())->send(new ClaimSubmittedToMentor($claim));

        return redirect('/expense-claim/new')->with('status','Expense Claim Submitted for Processing');
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

        // Get expense if allowed
        if (!ExpenseClaim::find($id)) abort(404);
        $claim = ExpenseClaim::canSee()->whereId($id)->first();
        if (!$claim) abort(401,'Unauthorized');

        // Update the Expense Claim with New Status
        $claim->status = $request->status;

        if ($request->status == 'rejected' || $request->status == 'processed'){
            $claim->processed_by_id = $request->user()->id;
            $claim->processed_at = Carbon::now();
        }

        if ($request->status == 'processed' && $request->check_number) {
            $claim->check_number = $request->check_number;
        }

        // Save to Database
        $claim->save();

        // Send Emails
        if ($request->status == 'processed'){
            Mail::to($claim->mentor)->send(new ClaimProcessedToMentor($claim));
            return redirect('/expense-claim/'.$id)->with('status','Expense Claim Processed');
        }

        if ($request->status == 'rejected'){
            Mail::to($claim->mentor)->send(new ClaimRejectedToMentor($claim));
            return redirect('/expense-claim')->with('status','Expense Claim Rejected');
        }
    }

    public function export(Request $request){
        $query = ExpenseClaim::canSee()->orderBy('created_at','desc');

        if ($request->mentor_id) {
            $query->whereMentorId($request->mentor_id);
        }

        $claims = $query->get();

        return view('expense_claim.export')->with('expense_claims', $claims);
    }

    private function saveExpense(Request $request) {
        $claim = new ExpenseClaim();
        // should check report exists and owned by mentor
        $claim->report_id = $request->report_id; 
        $claim->mentor_id = $request->user()->id;
        $claim->save();

        // expense items
        $expenseItems = collect($request->expenses)->map(function($expenseItem) use(&$claim) { return 
            new Expense([
                'expense_claim_id' => $claim->id,
                'date' => Carbon::createFromFormat('d-m-Y',$expenseItem['date'])->setTime(0,0,0),
                'description' => $expenseItem['description'],
                'amount' => $expenseItem['amount']
                ]
            );
        });
        $claim->expenses()->saveMany($expenseItems);

        // reciepts
        $receipts = collect($request->receipts)->map(function($receipt) use(&$claim) { return
            new Receipt([
                'expense_claim_id' => $claim->id,
                'path' => $receipt->store('receipts')
            ]);
        });
        $claim->receipts()->saveMany($receipts);

        return $claim;
    }

}
