<?php

namespace App\Http\Controllers\Settings\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interactions\Settings\Profile\UpdateProfilePhoto;

class PhotoController extends Controller
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
     * Store the user's profile photo.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->interaction(
            new UpdateProfilePhoto(),
            $request
        );
    }
}
