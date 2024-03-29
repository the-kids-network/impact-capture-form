<?php

namespace App\Domains\UserManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the user's password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if (! Hash::check($request->current_password, $request->user()->password)) {
            return response()->json([
                'errors' => [
                    'current_password' => ['The given password does not match our records.']
                ]
            ], 422);
        }

        $request->user()->forceFill([
            'password' => bcrypt($request->password)
        ])->save();

        return response()->json([
            'status' => "Your password has been updated!"
        ]);
    }
}
