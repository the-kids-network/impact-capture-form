<?php

namespace App\Domains\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Domains\UserManagement\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin')->only('delete', 'restore');
    }

    public function delete(Request $request) {
        if ($request->really_delete) {
            $user = User::withTrashed()->find($request->user_id);
            $user->forceDelete();
            return redirect()->back()->with('status', 'Deleted user');
        } else {
            $user = User::find($request->user_id);
            $user->delete();
            return redirect()->back()->with('status', 'Deactivated user');
        }
    }

    public function restore(Request $request) {
        User::withTrashed()
            ->where('id', $request->user_id)
            ->restore();

        return redirect()->back()->with('status', 'Restored user');
    }

}
