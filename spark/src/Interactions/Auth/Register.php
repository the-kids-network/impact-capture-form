<?php

namespace Laravel\Spark\Interactions\Auth;

use Laravel\Spark\Spark;
use Illuminate\Support\Facades\DB;
use Laravel\Spark\Contracts\Http\Requests\Auth\RegisterRequest;
use Laravel\Spark\Contracts\Interactions\Auth\Register as Contract;
use Laravel\Spark\Contracts\Interactions\Auth\CreateUser as CreateUserContract;

class Register implements Contract
{

    /**
     * {@inheritdoc}
     */
    public function handle(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $this->createUser($request);
        });
    }

    /**
     * Create the user for the new registration.
     *
     * @param  RegisterRequest  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function createUser(RegisterRequest $request)
    {
        $user = Spark::interact(CreateUserContract::class, [$request]);

        return $user;
    }
}
