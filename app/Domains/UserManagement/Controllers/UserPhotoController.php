<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Interactions\UpdateProfilePhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserPhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->interaction(
            new UpdateProfilePhoto(),
            $request
        );
    }

    public function remove(Request $request)
    {
        $request->user()->unsetProfilePhoto();
    }
}
