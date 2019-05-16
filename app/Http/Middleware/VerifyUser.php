<?php

namespace App\Http\Middleware;

use Closure;

abstract class VerifyUser {

    public function handleFailure($request) {
        if ($request->user()) {
            $request->ajax() || $request->wantsJson()
                ? response('Unauthorized.', 401)
                : abort(401,'Unauthorized');
        } else {
            $request->ajax() || $request->wantsJson()
                ? response('Unauthorized.', 401)
                : redirect()->guest('login');
        }
    }
}
