<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function interaction($interaction, Request $request )
    {
        $interaction->validator($request->user(), $request->all())->validate();

        return $interaction->handle($request->user(), $request->all());
    }

    public function handleError($validator) {
        $error = [
            "message" => "The given data was invalid.",
            "errors" => $validator->errors()
        ];
        return response()->json($error, 422);
    }
}
