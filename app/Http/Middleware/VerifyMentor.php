<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Middleware\VerifyUser;

class VerifyMentor extends VerifyUser
{
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isMentor()) {
            return $next($request);
        }

        $this->handleFailure($request);
    }
}
