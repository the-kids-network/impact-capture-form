<?php

namespace App\Configuration;

use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;

trait ProvidesScriptVariables
{

    public static function scriptVariables()
    {
        return [
            'csrfToken' => csrf_token(),
            'currencySymbol' => Cashier::usesCurrencySymbol(),
            'env' => config('app.env'),
            'state' => [
                'user' => Auth::user()
            ],
            'userId' => Auth::id(),
        ];
    }
}
