<?php

namespace App\Http\Middleware;

use Laravel\Spark\Spark;
use Closure;

class VerifyManager
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
        if ($request->user() && ($request->user()->isManager() || $request->user()->isAdmin() || Spark::developer($request->user()->email) || $request->user()->isAdmin())) {
            return $next($request);
        }

        return $request->ajax() || $request->wantsJson()
            ? response('Unauthorized.', 401)
            : abort(404,'Unauthorized');
    }
}
