<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\PlannedSession;
use App\MentorLeave;
use App\Mentee;
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
        $plannedSessionEvents = $this->getPlannedSessionCalendarEvents()->toArray();
        $mentorLeaveEvents = $this->getMentorLeaveCalendarEvents()->toArray();
        $allEvents = array_merge($plannedSessionEvents, $mentorLeaveEvents);

        $calendar = $this->createCalendar($allEvents);

        return view('calendar.index')->with('calendar', $calendar);
    }

    private function getPlannedSessionCalendarEvents() {
        return PlannedSession::canSee()->get()
            ->map(function($plannedSession) {
                return $this->mapPlannedSessionToCalendarEvent($plannedSession);
            });
    }

    private function mapPlannedSessionToCalendarEvent($plannedSession) {
        if (Auth::user()->isMentor()) {
            $title = $plannedSession->mentee()->name;
        } else {
            $mentor = $plannedSession->mentee()->mentor;
            $title = is_null($mentor) ? 'NO MENTOR' : $mentor->name;
        }

        return \Calendar::event(
            $title,
            true,
            new \DateTime($plannedSession['date']),
            new \DateTime($plannedSession['date']),
            $plannedSession->id,
            [
                'url' => 'planned-session/' . $plannedSession->id,
                'color' => '#34A5EF'
            ]
        );
    }

    private function getMentorLeaveCalendarEvents() {
        return MentorLeave::canSee()->get()
            ->map(function($leave) {
                return $this->mapMentorLeaveToCalendarEvent($leave);
            });
    }

    private function mapMentorLeaveToCalendarEvent($mentorLeave) {
        if (Auth::user()->isMentor()) {
            $title = 'Leave';
            $title = ($mentorLeave->description) ? $title.' - '.$mentorLeave->description : $title;
        } else {
            $title = $mentorLeave->mentor->name.' Leave';
        }
        return \Calendar::event(
            $title,
            true,
            new \DateTime($mentorLeave['start_date']),
            (new \DateTime($mentorLeave['end_date']))->modify('+1 day'),
            $mentorLeave->id,
            [
                'url' => 'mentor/leave/' . $mentorLeave->id,
                'color' => '#A41250'
            ]
        );
    }

    private function createCalendar($events) {
        return \Calendar::addEvents($events)
            ->setOptions([
                'header' => array('left' => 'prev,today,next', 'center' => 'title', 'right' => false),
                'buttonText' => array('today' => 'Now')
            ]);
    }
}
