<?php

namespace App\Http\Controllers;

use App\ActivityType;
use App\EmotionalState;
use App\PhysicalAppearance;
use App\SessionRating;
use App\Schedule;
use App\Mentee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('mentorOnly')->except('show','deleteAll');
        $this->middleware('dev')->only('deleteAll');
    }


    public function show(Request $request)
    {
        if($request->user()->isDeveloper()){
            return view('admin.index');
        }

        if($request->user()->isFinance()){
            return view('finance.index');
        }

        if($request->user()->isManager()){
            return view('manager.index');
        }

        if($request->user()->isMentor()){
            return view('mentor.index');
        }

    }

    /**
     * Show the report form.
     *
     * @return Response
     */
    public function reports(Request $request)
    {
        return view('mentor.report')
            ->with('mentees',$request->user()->mentees)
            ->with('activity_types', ActivityType::all())
            ->with('physical_appearances',PhysicalAppearance::all())
            ->with('emotional_states',EmotionalState::all())
            ->with('session_ratings',SessionRating::selectable())
            ->with('reports', $request->user()->reports()->orderBy('created_at','desc')->get() );
    }

    public function calendar(Request $request)
    {
        Log::alert(Schedule::all());
        $events = array();
        foreach (Schedule::all() as $schedule)
        {
            $testing = $schedule->mentee()->first();
            Log::alert($testing);
            $mentee = Mentee::where("id", $schedule->mentee()->first()->id);
            array_push($events, \Calendar::event(
                  $mentee->first()->getNameAttribute(),
                  true,
                  new \DateTime($schedule['next_session_date']),
                  new \DateTime($schedule['next_session_date'])
              ));
        }

        Log::alert($events);
        $calendar = \Calendar::addEvents($events);

        return view('mentor.calendar')
            ->with('calendar', $calendar);
    }

    /**
     *
     * Show the Expense Claim Form
     * @param Request $request
     * @return $this
     */
    public function expense_claims(Request $request)
    {
        return view('mentor.expense-claim')
            ->with('reports', $request->user()->reports()->orderBy('created_at','desc')->get() )
            ->with('claims', $request->user()->expense_claims()->orderBy('created_at','desc')->get() );
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll(){

        // Truncate Reports and Expense Claims Table
        DB::table('reports')->truncate();
        DB::table('expense_claims')->truncate();
        DB::table('expenses')->truncate();
        DB::table('receipts')->truncate();

        // Delete Receipts
        $receipts = Storage::files('receipts');
        Storage::delete($receipts);

        // Return Home
        return redirect('/home')->with('status','All Reports and Expense Claims Deleted');
    }

}
