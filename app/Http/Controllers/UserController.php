<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\User;

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
            return redirect('/roles/mentor')->with('status', 'Deleted user');
        } else {
            $user = User::find($request->user_id);
            $user->delete();
            return redirect('/roles/mentor')->with('status', 'Deactivated user');
        }
    }

    public function restore(Request $request) {
        User::withTrashed()
            ->where('id', $request->user_id)
            ->restore();

        return redirect('/roles/mentor')->with('status', 'Restored user');
    }

}
