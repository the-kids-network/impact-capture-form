<?php

namespace App\Contracts\Interactions\Auth;

use App\Contracts\Http\Requests\Auth\RegisterRequest;

interface Register
{
    /**
     * Get a validator instance for the request.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Validation\Validator
     */
    public function validator($user, array $data);

    /**
     * Create a new user instance in the database.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function handle($user, array $data);
}
