<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,mentor,manager')->only('show');
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

}
