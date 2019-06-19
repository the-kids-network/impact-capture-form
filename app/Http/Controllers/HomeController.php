<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,mentor,manager')->only('show');
        $this->middleware('admin')->only('deleteAll');
    }

    public function show(Request $request) {
        if ($request->user()->isAdmin()){
            return view('admin.index');
        }
        else if ($request->user()->isManager()){
            return view('manager.index');
        }
        else if ($request->user()->isMentor()){
            return view('mentor.index');
        } else {
            abort(401,'Unauthorized');
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll() {
        // Truncate Reports and Expense Claims Table
        DB::table('reports')->truncate();
        DB::table('expense_claims')->truncate();
        DB::table('expenses')->truncate();
        DB::table('receipts')->truncate();

        // Delete Receipts
        $receipts = Storage::files('receipts');
        Storage::delete($receipts);

        // Return Home
        return redirect('/home')->with('status','All Reports and Expense Claims Deleted');
    }

}
