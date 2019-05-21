<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Mentee;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ScheduleController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function index(Request $request) {
        return view('schedule.index')
            ->with('mentees', Mentee::canSee()->get());
    }

    public function show($id) {
        $schedule = Schedule::canSee()->find($id);
        if (!$schedule) {
            abort(401, 'Unauthorized'); 
        }

        return view('schedule.show', compact('schedule'))
            ->with('schedule', $schedule)
            ->with('session_date', $schedule->next_session_date->format('m/d/Y'));
    }

    public function destroy($id) {
        $allowableSchedules = Schedule::canSee();
        $scheduleToDelete = $allowableSchedules->find($id);
        if (!$scheduleToDelete) {
            abort(401, 'Unauthorized'); 
        }

        Schedule::destroy($scheduleToDelete->id);

        return redirect('/calendar');
    }

    public function store(Request $request) {
        $allowableMentees = Mentee::canSee();
        if (!$allowableMentees->find($request->mentee_id)) {
            abort(401,'Unauthorized'); 
        }
        
        $schedule = Schedule::find($request->id);
        if (!$schedule) {
            $schedule = new Schedule();
        }

        $schedule->mentee_id = $request->mentee_id;
        $schedule->next_session_date = Carbon::createFromFormat('m/d/Y',$request->next_session_date);
        $schedule->next_session_location = $request->next_session_location;
        $schedule->save();

        return redirect('/calendar');
    }
}
