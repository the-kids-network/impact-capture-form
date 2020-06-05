<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\SessionReports\Models\SafeguardingConcernLookup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SafeguardingConcernController extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get');
    }

    /**
     * REST controllers
     */
    public function get(Request $request) {
        return response()->json(SafeguardingConcernLookup::$values);
    }

}
