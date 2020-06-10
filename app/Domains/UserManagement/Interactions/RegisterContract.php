<?php

namespace App\Domains\UserManagement\Interactions;

interface RegisterContract
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
