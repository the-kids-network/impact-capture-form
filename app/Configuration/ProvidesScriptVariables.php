<?php

namespace App\Configuration;

use Illuminate\Support\Facades\Auth;

trait ProvidesScriptVariables
{

    public static function scriptVariables()
    {
        return [
            'csrfToken' => csrf_token(),
            'env' => config('app.env'),
            'state' => [
                'user' => Auth::user()
            ],
            'userId' => Auth::id(),
        ];
    }
}
