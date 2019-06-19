<?php

namespace App\Repositories;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Repositories\UserRepository as UserRepositoryContract;

class UserRepository implements UserRepositoryContract
{
    /**
     * {@inheritdoc}
     */
    public function current()
    {
        if (Auth::check()) {
            return $this->find(Auth::id());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        $user = User::where('id', $id)->first();

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $user = new User();

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ])->save();

        return $user;
    }
}
