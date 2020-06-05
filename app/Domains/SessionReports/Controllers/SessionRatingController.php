<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\SessionReports\Models\SessionRating;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionRatingController extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get');
    }

    /**
     * REST controllers
     */
    public function get(Request $request) {
        $ratings = SessionRating::selectable();

        return response()->json($ratings);
    }

}
