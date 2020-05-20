<?php

namespace App\Domains\Calendar\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Calendar\Services\MenteeLeaveService;
use App\Domains\Calendar\Services\MentorLeaveService;
use App\Domains\Calendar\Services\PlannedSessionService;
use Illuminate\Http\Request;

class CalendarController extends Controller {

    private $plannedSessionService;
    private $mentorLeaveService;
    private $menteeLeaveService;

    public function __construct(PlannedSessionService $plannedSessionService,
                                MentorLeaveService $mentorLeaveService,
                                MenteeLeaveService $menteeLeaveService) {
        $this->plannedSessionService = $plannedSessionService;
        $this->mentorLeaveService = $mentorLeaveService;
        $this->menteeLeaveService = $menteeLeaveService;
        
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function index(Request $request) {
        $plannedSessions = $this->getPlannedSessions();
        $mentorLeaves = $this->getMentorLeaves();
        $menteeLeaves = $this->getMenteeLeaves();
        $events = array();
        $events['planned_sessions'] = $plannedSessions;
        $events['mentor_leaves'] = $mentorLeaves;
        $events['mentee_leaves'] = $menteeLeaves;

        return view('calendar.index')->with('events', $events);
    }

    private function getPlannedSessions() {
        $plannedSessions =  $this->plannedSessionService->getPlannedSessions()
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
        $leaves = $this->mentorLeaveService->getMentorLeaves()
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

    private function getMenteeLeaves() {
        $leaves =  $this->menteeLeaveService->getMenteeLeaves()
                        ->map(function($leave) {
                            return $this->transformMenteeLeaves($leave);
                        })
                        ->toArray();

        return array_values($leaves);
    }

    private function transformMenteeLeaves($leave) {
        $event = array();
        $event['mentor'] = (isset($leave->mentee->mentor)) ? $leave->mentee->mentor->name : null;
        $event['mentee'] = $leave->mentee->name;
        $event['id'] = $leave->id;
        $event['start_date'] = $leave->start_date;
        $event['end_date'] = $leave->end_date->modify('+1 day');
        $event['description'] = $leave->description;
        return $event;
    }
}
