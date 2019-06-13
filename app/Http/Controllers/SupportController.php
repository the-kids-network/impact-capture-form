<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interactions\Support\SendSupportEmail;

class SupportController extends Controller
{
    /**
     * Send a customer support request e-mail.
     *
     * @param  Request  $request
     * @return Response
     */
    public function sendEmail(Request $request)
    {
        $this->interaction(
            new SendSupportEmail(),
            $request
        );
    }
}
