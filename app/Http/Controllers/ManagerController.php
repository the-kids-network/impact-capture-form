<?php

namespace App\Http\Controllers;

use App\ExpenseClaim;
use App\Report;
use App\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{


    public function __construct()
    {
        $this->middleware('manager');
    }

    public function index()
    {
        return view('manager.index');
    }

    public function reviewClaims(Request $request)
    {
        $mentors = $request->user()->assignedMentors;

        return view('manager.review-claims')
            ->with('mentors',$mentors);
    }

    public function reviewReports(Request $request)
    {
        $ids = [];
        foreach($request->user()->assignedMentors as $mentor){
            $ids[] = $mentor->id;
        }

        $reports = Report::whereIn('mentor_id', $ids )->get();
        return view('report.index')->with('reports',$reports);
    }

    public function exportReports(Request $request)
    {
        $ids = [];
        foreach($request->user()->assignedMentors as $mentor){
            $ids[] = $mentor->id;
        }

        $reports = Report::whereIn('mentor_id', $ids )->get();
        return view('report.export')->with('reports',$reports);
    }

    public function exportExpenseClaims(Request $request)
    {
        $ids = [];
        foreach($request->user()->assignedMentors as $mentor){
            $ids[] = $mentor->id;
        }

        $expense_claims = ExpenseClaim::whereIn('mentor_id', $ids)->get();
        return view('expense_claim.export')->with('expense_claims', $expense_claims);
    }
}
