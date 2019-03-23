<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Mentee;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $schedule = new Schedule();
        Log::info(Mentee::allForUser($request->user()));
        return view('schedule.index')
            ->with('mentees', Mentee::allForUser($request->user()));
    }

    public function show($id)
    {
        $schedule = Schedule::find($id);

        return view('schedule.show', compact('schedule'))
            ->with('schedule', $schedule)
            ->with('session_date', $schedule->next_session_date->format('m/d/Y'));
    }

    public function destroy($id)
    {
        Schedule::destroy($id);

        return redirect('/calendar');
    }

    public function store(Request $request)
    {
        $schedule = Schedule::find($request->id);

        if (!$schedule)
        {
            $schedule = new Schedule();
        }

        $schedule->mentee_id = $request->mentee_id;
        $schedule->next_session_date = Carbon::createFromFormat('m/d/Y',$request->next_session_date);
        $schedule->next_session_location = $request->next_session_location;
        $schedule->save();

        return redirect('/calendar');
    }
}
