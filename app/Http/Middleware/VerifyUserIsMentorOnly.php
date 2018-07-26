<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserIsMentorOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // User is logged in and doesn't have any role
        // i.e. they are only a mentor
        // not a manager
        // not finance
        // not even admin
        if ($request->user() && $request->user()->isMentorOnly() ) {
            return $next($request);
        }

        return $request->ajax() || $request->wantsJson()
            ? response('Unauthorized.', 401)
            : abort(404,'Unauthorized');
    }
}
