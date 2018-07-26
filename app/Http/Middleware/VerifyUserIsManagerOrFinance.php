<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Spark\Spark;

class VerifyUserIsManagerOrFinance
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
        if ($request->user() && ($request->user()->isFinance() || Spark::developer($request->user()->email) || $request->user()->isManager()) ) {
            return $next($request);
        }

        return $request->ajax() || $request->wantsJson()
            ? response('Unauthorized.', 401)
            : abort(404,'Unauthorized');
    }
}
