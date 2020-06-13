<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function setRole(Request $request, $userId, $role) {
        $validator = Validator::make(
            ['role' => $role], 
            ['role' => 'required|in:admin,manager'], 
            ['role.required' => 'Role to set required',
             'role.in' => 'Role should be admin or manager']
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = User::find($userId);
        if ($user->role == 'manager') {
            foreach($user->assignedMentors as $mentor){
                $mentor->manager_id = NULL;
                $mentor->save();
            }
        }

        $user->role = $role;
        $user->save();
        return redirect()->back()->with('status', 'Role "'.$request->role.'" set for user "'.$user->name.'"');
    }

    public function removeRole(Request $request, $userId, $role) {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->withErrors('User does not exist');
        }
        if ($user->role != $role) {
            return redirect()->back()->withErrors($user->name.' does not have role" '.$role.'"');
        }
#
        if ($user->role == 'manager') {
            foreach($user->assignedMentors as $mentor){
                $mentor->manager_id = NULL;
                $mentor->save();
            }
        }

        $user->role = NULL;
        $user->save();

        return redirect()->back()->with('status', 'Role "'.$role.'" removed for user "'.$user->name.'"');
    }
}
