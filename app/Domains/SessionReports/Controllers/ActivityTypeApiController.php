<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\SessionReports\Models\ActivityType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityTypeApiController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get');
    }

    /**
     * REST controllers
     */
    public function get(Request $request) {
        $query = ActivityType::query();
        if ($request->trashed) $query->withTrashed();
        $activityTypes = $query->get();

        return response()->json($activityTypes);
    }

}
