<?php

namespace Laravel\Spark;

use Laravel\Spark\Contracts\Repositories\UserRepository;
use Laravel\Spark\Contracts\InitialFrontendState as Contract;

class InitialFrontendState implements Contract
{
    /**
     * {@inheritdoc}
     */
    public function forUser($user)
    {
        return [
            'user' => $this->currentUser()
        ];
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function currentUser()
    {
        return Spark::interact(UserRepository::class.'@current');
    }

}
