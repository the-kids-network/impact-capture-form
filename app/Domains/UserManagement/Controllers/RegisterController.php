<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Dtos\RegisterRequest;
use App\Domains\UserManagement\Interactions\Register;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RedirectsUsers;

class RegisterController extends Controller
{
    use RedirectsUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the application registration form.
     *
     * @param  Request  $request
     * @return Response
     */
    public function showRegistrationForm(Request $request)
    {
        return view('user_management.register.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  RegisterRequest  $request
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        
        $this->interaction(
            new Register(),
            $request
        );

        return response()->json([
            'status' => "Registration was successful. Please ask the user to login."
        ]);
    }
}
