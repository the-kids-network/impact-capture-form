<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\PlannedSession;
use App\MentorLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function index(Request $request) {
        $plannedSessionEvents = $this->getPlannedSessions();
        $mentorLeaveEvents = $this->getMentorLeaves();
        $events = array();
        $events['planned_sessions'] = $plannedSessionEvents;
        $events['mentors_leaves'] = $mentorLeaveEvents;

        return view('calendar.index')->with('events', $events);
    }

    private function getPlannedSessions() {
        $plannedSessions =  PlannedSession::canSee()
                            ->get()
                            ->map(function($plannedSession) {
                                return $this->transformPlannedSession($plannedSession);
                            })
                            ->toArray();
        
            // convert to sequential array to ensure json_encode works properly
            return array_values($plannedSessions);
    }

    private function transformPlannedSession($plannedSession) {
        $mentee = $plannedSession->mentee;
        $mentor = $plannedSession->mentee->mentor;
        $event = array();
        $event['mentor'] = is_null($mentor) ? 'NO MENTOR' : $mentor->name;
        $event['mentee'] = $mentee->name;
        $event['id'] = $plannedSession->id;
        $event['start_date'] = $plannedSession->date;
        $event['end_date'] = $plannedSession->date->modify('+1 day');
        $event['location'] = $plannedSession->location;
        return $event;
    }

    private function getMentorLeaves() {
        $leaves =  MentorLeave::canSee()
                        ->get()
                        ->map(function($leave) {
                            return $this->transformMentorLeaves($leave);
                        })
                        ->toArray();

        return array_values($leaves);
    }

    private function transformMentorLeaves($mentorLeave) {
        $event = array();
        $event['mentor'] = $mentorLeave->mentor->name;
        $event['id'] = $mentorLeave->id;
        $event['start_date'] = $mentorLeave->start_date;
        $event['end_date'] = $mentorLeave->end_date->modify('+1 day');
        $event['description'] = $mentorLeave->description;
        return $event;
    }
}
