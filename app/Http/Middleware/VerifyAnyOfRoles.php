<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAnyOfRoles
{
    public function handle($request, Closure $next, ... $roles)
    {
        $user = $request->user();

        foreach($roles as $role) {
            if($user->hasRole($role))
                return $next($request);
        }

        return $request->ajax() || $request->wantsJson()
            ? response('Unauthorized.', 401)
            : abort(404,'Unauthorized');
    }
}
