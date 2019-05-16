<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Middleware\VerifyUser;

class VerifyAnyOfRoles extends VerifyUser {
    
    public function handle($request, Closure $next, ... $roles) {
        $user = $request->user();
        foreach($roles as $role) {
            if($user && $user->hasRole($role)) {
                return $next($request);
            }
        }

        $this->handleFailure($request);
    }
}
