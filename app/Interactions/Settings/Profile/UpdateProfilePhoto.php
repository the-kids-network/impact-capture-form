<?php

namespace App\Interactions\Settings\Profile;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Contracts\Interactions\Settings\Profile\UpdateProfilePhoto as Contract;

class UpdateProfilePhoto implements Contract
{
    public function __construct()
    {
        Image::configure(array('driver' => 'gd'));
    }

    public function validator($user, array $data)
    {
        $messages = [
            'photo.max' => 'The photo must be max of 4MB in size',
            'photo.image' => 'The file must be an image',
        ];

        return Validator::make($data, [
            'photo' => 'required|image|max:4000',
        ], $messages);
    }

    public function handle($user, array $data)
    {
        $user->setProfilePhoto($data['photo']);
    }
}
