<?php

namespace App\Domains\UserManagement\Interactions;

use Illuminate\Support\Facades\Validator;

class UpdateContactInformation implements UpdateContactInformationContract
{
    /**
     * {@inheritdoc}
     */
    public function validator($user, array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($user, array $data)
    {
        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
        ])->save();


        return $user;
    }
}
