<?php

namespace App\Http\Controllers;

use App\PlannedSession;
use App\Mentee;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function index(Request $request) {
        $allowableMentees = Mentee::canSee()->get();

        $calendar = $this->createCalendar($allowableMentees);

        return view('calendar.index')->with('calendar', $calendar);
    }

    private function createCalendar($mentees) {
        $events = $mentees->flatmap(function($mentee) {
            return $mentee->plannedSessions()->map(function($plannedSession) use(&$mentee) {
                return \Calendar::event(
                    is_null($mentee->mentor()->first()) ? 'NO MENTOR' : $mentee->mentor()->first()->name,
                    true,
                    new \DateTime($plannedSession['date']),
                    new \DateTime($plannedSession['date']),
                    $plannedSession->id,
                    ['url' => 'planned-session/' . $plannedSession->id]
                );
            });
        });

        return \Calendar::addEvents($events)
            ->setOptions([
                'header' => array('left' => 'prev,today,next', 'center' => 'title', 'right' => false),
                'buttonText' => array('today' => 'Now')
            ]);
    }
}
