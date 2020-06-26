<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function current() {
        if (Auth::check()) {
            return Auth::user();
        }

        return null;
    }

    public function get(Request $request) {
        $validator = Validator::make(
            ['role' => $request->role], 
            ['role' => 'required|in:mentor'], 
            ['role.required' => 'User role parameter is required',
             'role.in' => 'Requested role can only be \'mentor\' at present for bulk search']
        );

        if ($validator->fails()) {
            return $this->handleError($validator);
        }

        $users = User::canSee()->with('mentees')->whereRole(null)->get();

        $payload = $users->map(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ? $user->role : 'mentor',
            'manager_id' => $user->manager_id,
            'mentees' => $user->mentees->map(fn($mentee) => [
                'id' => $mentee->id,
                'name' => $mentee->name,
            ])
        ]);

        return response()->json($payload);
    }
}
