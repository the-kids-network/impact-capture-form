<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\PlannedSession;
use App\Mentee;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PlannedSessionController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function create(Request $request) {
        return view('planned_session.new')
            ->with('mentees', Mentee::canSee()->get());
    }

    public function show($id) {
        $plannedSession = PlannedSession::canSee()->find($id);
        if (!$plannedSession) {
            abort(401, 'Unauthorized'); 
        }

        return view('planned_session.show', compact('plannedSession'))
            ->with('plannedSession', $plannedSession)
            ->with('session_date', $plannedSession->date->format('m/d/Y'));
    }

    public function destroy($id) {
        $allowablePlannedSessions = PlannedSession::canSee();
        $plannedSessionToDelete = $allowablePlannedSessions->find($id);
        if (!$plannedSessionToDelete) {
            abort(401, 'Unauthorized'); 
        }

        PlannedSession::destroy($plannedSessionToDelete->id);

        return redirect('/calendar');
    }

    public function store(Request $request) {
        $request->validate([
            'next_session_date' => 'required|date|date_format:m/d/Y',
            'next_session_location' => 'required|string|max:50'
        ]);    

        $allowableMentees = Mentee::canSee();
        if (!$allowableMentees->find($request->mentee_id)) {
            abort(401,'Unauthorized'); 
        }
        
        $plannedSession = PlannedSession::find($request->id);
        if (!$plannedSession) {
            $plannedSession = new PlannedSession();
        }

        $plannedSession->mentee_id = $request->mentee_id;
        $plannedSession->date = Carbon::createFromFormat('m/d/Y',$request->next_session_date);
        $plannedSession->location = $request->next_session_location;
        $plannedSession->save();

        return redirect('/calendar');
    }
}
