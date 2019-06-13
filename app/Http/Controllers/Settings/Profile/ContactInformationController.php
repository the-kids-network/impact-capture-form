<?php

namespace App\Http\Controllers\Settings\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interactions\Settings\Profile\UpdateContactInformation;

class ContactInformationController extends Controller
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
    }
}
