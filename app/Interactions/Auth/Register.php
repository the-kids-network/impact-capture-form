<?php

namespace App\Interactions\Auth;

use Illuminate\Support\Facades\DB;
use App\Contracts\Http\Requests\Auth\RegisterRequest;
use App\Contracts\Interactions\Auth\Register as Contract;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;

class Register implements Contract
{
    /**
     * {@inheritdoc}
     */
    public function validator($user, array $data)
    {
        $validator = $this->baseValidator($user, $data);

        return $validator;
    }

    /**
     * Create a base validator instance for creating a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Validation\Validator
     */
    protected function baseValidator($user, array $data)
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
