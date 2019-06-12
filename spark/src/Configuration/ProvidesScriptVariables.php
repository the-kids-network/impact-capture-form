<?php

namespace Laravel\Spark\Configuration;

use Laravel\Spark\Spark;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;
use Laravel\Spark\Contracts\InitialFrontendState;

trait ProvidesScriptVariables
{
    /**
     * Get the default JavaScript variables for Spark.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
            'csrfToken' => csrf_token(),
            'currencySymbol' => Cashier::usesCurrencySymbol(),
            'env' => config('app.env'),
            'state' => Spark::call(InitialFrontendState::class.'@forUser', [Auth::user()]),
            'userId' => Auth::id(),
        ];
    }
}
