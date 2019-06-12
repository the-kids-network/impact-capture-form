<?php

namespace Laravel\Spark\Http\Controllers\Auth;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Spark\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Spark\Contracts\Interactions\Settings\Security\VerifyTwoFactorAuthToken as Verify;

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

        $this->redirectTo = Spark::afterLoginRedirect();
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('spark::auth.login');
    }

    /**
     * {@inheritdoc}
     */
    public function login(Request $request)
    {
        if ($request->filled('remember')) {
            $request->session()->put('spark:auth-remember', $request->remember);
        }

        $user = Spark::user()->where('email', $request->email)->first();

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
        return redirect()->intended($this->redirectPath());
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

        return redirect(
            property_exists($this, 'redirectAfterLogout')
                    ? $this->redirectAfterLogout : '/'
        );
    }
}
