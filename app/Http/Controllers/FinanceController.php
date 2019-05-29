<?php

namespace App\Http\Controllers;

use App\ExpenseClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function processExpenseClaims() {
        $pending_claims = ExpenseClaim::whereStatus('pending')->orderBy('created_at','desc')->get();
        $user_processed_claims = Auth::user()->processedAndRejectedClaims()->orderBy('created_at','desc')->get();

        return view('finance.process-expense-claims')
            ->with('pending_claims', $pending_claims)
            ->with('processed_claims', $user_processed_claims);
    }

    public function exportExpenseClaims(Request $request) {
        $user_processed_claims = $request->user()->processedAndRejectedClaims()->orderBy('created_at','desc')->get();
        return view('expense_claim.export')->with('expense_claims', $user_processed_claims);
    }
}
