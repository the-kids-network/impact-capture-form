<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\SessionReports\Models\EmotionalState;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmotionalStateApiController extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get');
    }

    /**
     * REST controllers
     */
    public function get(Request $request) {
        $query = EmotionalState::query();
        if ($request->trashed) $query->withTrashed();
        $emotionalStates = $query->get();

        return response()->json($emotionalStates);
    }

}
