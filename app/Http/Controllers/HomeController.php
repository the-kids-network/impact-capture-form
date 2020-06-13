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
            return view('user_portal.admin.index');
        }
        else if ($request->user()->isManager()){
            return view('user_portal.manager.index');
        }
        else if ($request->user()->isMentor()){
            return view('user_portal.mentor.index');
        } else {
            abort(401,'Unauthorized');
        }
    }

}
