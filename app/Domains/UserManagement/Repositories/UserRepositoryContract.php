<?php

namespace App\Domains\UserManagement\Repositories;

interface UserRepositoryContract
{
    /**
     * Get the current user of the application.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function current();

    /**
     * Get the user with the given ID.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function find($id);

    /**
     * Create a new user with the given data.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function create(array $data);
}
