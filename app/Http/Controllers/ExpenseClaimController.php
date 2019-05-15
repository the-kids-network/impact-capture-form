<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseClaim;
use App\Mail\ClaimProcessedToFinance;
use App\Mail\ClaimProcessedToManager;
use App\Mail\ClaimProcessedToMentor;
use App\Mail\ClaimRejectedToFinance;
use App\Mail\ClaimRejectedToManager;
use App\Mail\ClaimRejectedToMentor;
use App\Mail\ClaimSubmittedToManager;
use App\Mail\ClaimSubmittedToMentor;
use App\Receipt;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ExpenseClaimController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dev')->only('export','index');
        $this->middleware('manager')->except('store','show','update');
        $this->middleware('mentorOnly')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('expense_claim.index')->with('expense_claims',ExpenseClaim::orderBy('created_at','desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'expenses.*.date' => 'required|date|before_or_equal:today',
            'expenses.*.amount' => 'required|numeric',
            'expenses.*.description' => 'required',
            'receipts.*' => 'mimes:jpeg,png,gif,pdf,jpg|max:5000'
        ]);

        $claim = new ExpenseClaim();
        $claim->report_id = $request->report_id;
        $claim->mentor_id = $request->user()->id;
        $claim->save();

        $expenses = array();
        foreach ($request->expenses as $expense) {
            array_push($expenses, new Expense([
                'expense_claim_id' => $claim->id,
                'date' => Carbon::createFromFormat('m/d/Y',$expense['date'])->format('Y-m-d H:i:s'),
                'description' => $expense['description'],
                'amount' => $expense['amount']
            ]));
        }
        $claim->expenses()->saveMany($expenses);

        if($request->receipts){
            $receipts = array();
            foreach($request->receipts as $receipt){
                array_push($receipts, new Receipt([
                    'expense_claim_id' => $claim->id,
                    'path' => $receipt->store('receipts')
                ]));
            }
            $claim->receipts()->saveMany($receipts);
        }

        // Send an Email to the Mentor
        Mail::to($request->user())->send(new ClaimSubmittedToMentor($claim));

        // Send an Email to the Assigned Manager if any
        if($request->user()->manager){
            Mail::to($request->user()->manager)->send(new ClaimSubmittedToManager($claim));
        }

        return redirect('/my-expense-claims')->with('status','Expense Claim Submitted for Processing');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('expense_claim.show')->with('expense_claim',ExpenseClaim::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        // Update the Expense Claim with New Status
        $claim = ExpenseClaim::find($id);
        $claim->status = $request->status;

        if ($request->status == 'rejected' || $request->status == 'processed'){
            $claim->processed_by_id = $request->user()->id;
            $claim->processed_at = Carbon::now();
        }

        if ($request->status == 'processed') {
            // Update the Expense Claim with the Check Number if Provided
            if ($request->check_number) {
                $claim->check_number = $request->check_number;
            }
        }

        // Save to Database
        $claim->save();

        // Send Emails
        if ($request->status == 'processed'){
            // Send Processed Emails to Mentor, Approving Manager and Processor (Finance)
            Mail::to($request->user())->send(new ClaimProcessedToFinance($claim));
            if ($claim->mentor->manager) {
                Mail::to($claim->mentor->manager)->send(new ClaimProcessedToManager($claim));
            }
            Mail::to($claim->mentor)->send(new ClaimProcessedToMentor($claim));

            return redirect('/expense-claim/'.$id)->with('status','Expense Claim Processed');
        }

        if ($request->status == 'rejected'){
            // Send Rejection Emails
            Mail::to($request->user())->send(new ClaimRejectedToFinance($claim));
            if ($claim->mentor->manager) {
                Mail::to($claim->mentor->manager)->send(new ClaimRejectedToManager($claim));
            }
            Mail::to($claim->mentor)->send(new ClaimRejectedToMentor($claim));

            return redirect('/expense-claim')->with('status','Expense Claim Rejected');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }


    public function export(){
        return view('expense_claim.export')->with('expense_claims',ExpenseClaim::orderBy('created_at','desc')->get());
    }

}
