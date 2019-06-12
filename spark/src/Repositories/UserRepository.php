<?php

namespace Laravel\Spark\Repositories;

use Carbon\Carbon;
use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Auth;
use Laravel\Spark\Contracts\Repositories\UserRepository as UserRepositoryContract;

class UserRepository implements UserRepositoryContract
{
    /**
     * {@inheritdoc}
     */
    public function current()
    {
        if (Auth::check()) {
            return $this->find(Auth::id())->shouldHaveSelfVisibility();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        $user = Spark::user()->where('id', $id)->first();

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function search($query, $excludeUser = null)
    {
        $search = Spark::user()->with('subscriptions');

        // If a user to exclude was passed to the repository, we will exclude their User
        // ID from the list. Typically we don't want to show the current user in the
        // search results and only want to display the other users from the query.
        if ($excludeUser) {
            $search->where('id', '<>', $excludeUser->id);
        }

        return $search->where(function ($search) use ($query) {
            $search->where('email', 'like', $query)
                   ->orWhere('name', 'like', $query);
        })->get();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $user = Spark::user();

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ])->save();

        return $user;
    }
}
