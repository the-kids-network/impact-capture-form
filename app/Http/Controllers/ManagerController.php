<?php

namespace App\Http\Controllers;

use App\ExpenseClaim;
use App\Report;
use App\User;
use Illuminate\Http\Request;

class ManagerController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('manager');
    }

    public function index() {
        return view('manager.index');
    }

    public function viewExpenseClaims(Request $request) {
        // TODO: This can be simplified by just returning the expense claims rather than the mentors
        $mentors = User::canSee()->isMentor()->get();

        return view('manager.view-expense-claims')->with('mentors', $mentors);
    }

    public function exportExpenseClaims(Request $request) {
        $expense_claims = ExpenseClaim::canSee()->orderBy('created_at','desc')->get();
        return view('expense_claim.export')->with('expense_claims', $expense_claims);
    }
}
