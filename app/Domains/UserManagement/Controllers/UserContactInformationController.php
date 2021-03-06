<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Interactions\UpdateContactInformation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserContactInformationController extends Controller
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
     * Update the user's contact information settings.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $this->interaction(
            new UpdateContactInformation(),
            $request
        );

        return response()->json([
            'status' => "Your contact information has been updated!"
        ]);
    }
}
