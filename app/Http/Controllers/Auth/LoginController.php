<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        AuthenticatesUsers::login as traitLogin;
    }

    /**
     * Create a new login controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * {@inheritdoc}
     */
    public function login(Request $request)
    {
        if ($request->filled('remember')) {
            $request->session()->put('spark:auth-remember', $request->remember);
        }

        $user = User::where('email', $request->email)->first();

        return $this->traitLogin($request);
    }

    /**
     * Handle a successful authentication attempt.
     *
     * @param  Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return Response
     */
    public function authenticated(Request $request, $user)
    {
        return redirect()->intended('/home');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $this->guard()->logout();

        session()->flush();

        return redirect('/');
    }
}