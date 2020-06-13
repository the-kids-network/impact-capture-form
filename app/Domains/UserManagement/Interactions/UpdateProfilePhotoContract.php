<?php

namespace App\Domains\UserManagement\Interactions;

use App\Domains\UserManagement\Models\User;

interface UpdateProfilePhotoContract
{
    /**
     * Get a validator instance for the given data.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $data
     * @return \Illuminate\Validation\Validator
     */
    public function validator($user, array $data);

    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $data
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function handle(User $user, array $data);
}
