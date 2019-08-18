<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\PlannedSession;
use App\Mentee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlannedSessionChangedToManager;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PlannedSessionController extends Controller {

    private $validationRules = [
        'next_session_date' => 'required|date|date_format:m/d/Y',
        'next_session_location' => 'required|string|max:50'
    ];

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
            ->with('plannedSession', $plannedSession);
    }

    public function destroy($id) {
        $plannedSession = PlannedSession::canSee()->find($id);
        if (!$plannedSession) {
            abort(401, 'Unauthorized'); 
        }

        PlannedSession::destroy($plannedSession->id);

        // Send email if deleted by mentor and mentor has manager 
        $user = Auth::user();
        if ($user->isMentor() && isset($user->manager)) {
            Mail::to($user->manager)
                ->send(new PlannedSessionChangedToManager($user, $plannedSession, "delete"));
        }

        return redirect('/calendar');
    }

    public function store(Request $request) {
        $request->validate($this->validationRules);    

        // Authorize
        if (! Mentee::canSee()->find($request->mentee_id)) {
            abort(401, 'Unauthorized'); 
        }
        
        // Create planned session
        $plannedSession = new PlannedSession();
        $plannedSession = $this->mapRequestToPlannedSession($plannedSession, $request);

        // Check not a planned session for the same date/mentee
        $resp = $this->validatePreviousPlannedSessionDoesNotAlreadyExist($plannedSession);
        if($resp) return $resp;

        // Save it
        $plannedSession->save();

        return redirect('/calendar');
    }

    public function update(Request $request, $id) {
        $request->validate($this->validationRules);   

        // Authorize
        $plannedSession = PlannedSession::canSee()->find($id);
        if (!$plannedSession) {
            abort(401, 'Unauthorized');
        }
        if (!Mentee::canSee()->find($request->mentee_id)) {
            abort(401, 'Unauthorized'); 
        }

        // Create planned session
        $plannedSession = $this->mapRequestToPlannedSession($plannedSession, $request);

        // Check not a planned session for the same date/mentee
        $resp = $this->validatePreviousPlannedSessionDoesNotAlreadyExist($plannedSession);
        if($resp) return $resp;

        // Save it
        $plannedSession->save();
        $isDirty = !empty($plannedSession->getChanges());

        // Send email if changed by mentor and mentor has manager 
        $user = Auth::user();
        if ($user->isMentor() && isset($user->manager) && $isDirty) {
            Mail::to($user->manager)
                ->send(new PlannedSessionChangedToManager($user, $plannedSession, "change"));
        }

        return redirect('/calendar');
    }

    private function mapRequestToPlannedSession($plannedSession, $request) {
        $plannedSession->mentee_id = $request->mentee_id;
        $plannedSession->date = Carbon::createFromFormat('m/d/Y', $request->next_session_date)->setTime(0,0,0);
        $plannedSession->location = $request->next_session_location;
        return $plannedSession;
    }

    private function validatePreviousPlannedSessionDoesNotAlreadyExist($plannedSession) {
        $previouslyPlannedSession = $this->getPreviouslyPlannedSession(
            $plannedSession->id, $plannedSession->date, $plannedSession->mentee_id);
        if (isset($previouslyPlannedSession)){
            return back()->withInput()
                ->withErrors('Looks like you already have a session planned for that date and mentee. 
                    <a href="/planned-session/'.$previouslyPlannedSession->id.'">Change it here.</a>');
        }
    }

    private function getPreviouslyPlannedSession($id, $date, $menteeId) {
        $otherPlannedSessions = PlannedSession::canSee()
           ->where('id', '!=', $id)
           ->whereDate('date', '=', $date)
           ->whereMenteeId($menteeId)
           ->get();

        return $otherPlannedSessions->first();
   }
}
