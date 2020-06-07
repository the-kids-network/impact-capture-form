<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class UserApiController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function current() {
        $repo = new UserRepository();
        return $repo->current();
    }
}
