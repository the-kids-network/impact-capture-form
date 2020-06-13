<?php

namespace App\Domains\UserManagement\Interactions;

use App\Domains\UserManagement\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Register implements RegisterContract
{
    /**
     * {@inheritdoc}
     */
    public function validator($user, array $data)
    {
        $validator = $this->baseValidator($data);

        return $validator;
    }

    /**
     * Create a base validator instance for registering/creating a user.
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function baseValidator(array $data)
    {
        return Validator::make(
            $data, 
            $this->rules(),
            [], 
            []
        );
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function handle($user, array $data)
    {
        return DB::transaction(function () use ($data) {
            $repo = new UserRepository();
            return $repo->create($data);
        });
    }
}
