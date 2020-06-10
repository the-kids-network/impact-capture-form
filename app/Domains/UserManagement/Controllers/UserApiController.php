<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class UserApiController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function current() {
        $repo = new UserRepository();
        return $repo->current();
    }
}
