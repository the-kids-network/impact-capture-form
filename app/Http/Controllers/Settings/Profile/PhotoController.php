<?php

namespace App\Http\Controllers\Settings\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interactions\Settings\Profile\UpdateProfilePhoto;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;


class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->interaction(
            new UpdateProfilePhoto(),
            $request
        );
    }

    public function remove(Request $request)
    {
        $request->user()->removeProfilePhoto();
    }
}
