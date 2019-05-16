<?php

namespace App\Http\Controllers;

use App\ExpenseClaim;
use Illuminate\Http\Request;

class FinanceController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function processExpenseClaims() {
        return view('finance.process-expense-claims')
            ->with('claims', ExpenseClaim::where('status','pending')->orderBy('created_at','desc')->get());
    }

    public function exportExpenseClaims(Request $request) {
        $expense_claims = $request->user()->processedClaims;
        return view('expense_claim.export')->with('expense_claims', $expense_claims);
    }
}
