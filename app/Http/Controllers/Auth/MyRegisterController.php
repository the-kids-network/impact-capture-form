<?php

namespace App\Http\Controllers\Auth;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Laravel\Spark\Contracts\Http\Requests\Auth\RegisterRequest;
use Laravel\Spark\Http\Controllers\Auth\RegisterController as SparkRegisterController;

/**
 * Class MyRegisterController
 * @package App\Http\Controllers\Auth
 * Replaces Guest with Dev Middleware. Registration is now only allowed for Admins
 */
class MyRegisterController extends SparkRegisterController
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->redirectTo = Spark::afterLoginRedirect();
    }

    // Process Registration
    public function register(RegisterRequest $request)
    {
        return parent::register($request);
    }

    // Show Registration Form
    public function showRegistrationForm(Request $request)
    {
        return parent::showRegistrationForm($request);
    }

}