<?php

namespace App\Domains\Expenses\Controllers;

use App\Domains\Expenses\Models\ExpenseClaim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function processExpenseClaims() {
        $pending_claims = ExpenseClaim::whereStatus('pending')->orderBy('created_at','desc')->get();
        $user_processed_claims = Auth::user()->processedClaims()->orderBy('created_at','desc')->get();

        return view('expense_claims.process')
            ->with('pending_claims', $pending_claims)
            ->with('processed_claims', $user_processed_claims);
    }

    public function exportExpenseClaims(Request $request) {
        $user_processed_claims = $request->user()->processedClaims()->orderBy('created_at','desc')->get();
        return view('expense_claims.export')->with('expense_claims', $user_processed_claims);
    }
}
