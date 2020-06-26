<?php

namespace App\Domains\Expenses\Controllers;

use App\Domains\Expenses\Models\StatusLookup;
use App\Http\Controllers\Controller;

class StatusApiController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get');
    }

    public function get() {
        return response()->json(StatusLookup::$values);
    }
}
