<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Models\Mentee;
use App\Domains\UserManagement\Models\User;
use App\Http\Controllers\Controller;

class UserManagementPageController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function mentor(){
        return view('user_management.mentor')
            ->with('allMentors', User::mentor()->withDeactivated()->get())
            ->with('assignableMentors', User::mentor()->get())
            ->with('assignableMentees', Mentee::all());
    }

    public function manager(){
        return view('user_management.manager')
            ->with('users', User::all() );
    }

    public function admin(){
        return view('user_management.admin')
            ->with('users', User::all() );
    }
}
