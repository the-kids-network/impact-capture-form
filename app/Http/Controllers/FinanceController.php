<?php

namespace App\Http\Controllers;

use App\ExpenseClaim;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function reviewClaims()
    {
        return view('finance.review-claims')
            ->with('claims', ExpenseClaim::where('status','approved')->orderBy('created_at','desc')->get());

    }

    public function exportExpenseClaims(Request $request)
    {
        $expense_claims = $request->user()->processedClaims;
        return view('expense_claim.export')->with('expense_claims', $expense_claims);
    }
}
