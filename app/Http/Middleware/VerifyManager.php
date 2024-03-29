<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Middleware\VerifyUser;

class VerifyManager extends VerifyUser
{
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isManager()) {
            return $next($request);
        }

        $this->handleFailure($request);
    }
}
