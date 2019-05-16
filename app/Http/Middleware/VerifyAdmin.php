<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Middleware\VerifyUser;

class VerifyAdmin extends VerifyUser
{
    public function handle($request, Closure $next) {
        if ($request->user() && $request->user()->isAdmin() ) {
            return $next($request);
        }

        $this->handleFailure($request);
    }
}
