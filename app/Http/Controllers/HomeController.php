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

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,mentor,manager')->only('show', 'calendar');
        $this->middleware('admin')->only('deleteAll');
        $this->middleware('mentor')->only('newReport', 'newExpenseClaim');
    }

    public function show(Request $request) {
        if ($request->user()->isAdmin()){
            return view('admin.index');
        }
        else if ($request->user()->isManager()){
            return view('manager.index');
        }
        else if ($request->user()->isMentor()){
            return view('mentor.index');
        } else {
            abort(401,'Unauthorized');
        }
    }

    /**
     * Show the report form.
     *
     * @return Response
     */
    public function newReport(Request $request) {
        return view('mentor.report')
            ->with('mentees',$request->user()->mentees)
            ->with('activity_types', ActivityType::all())
            ->with('physical_appearances',PhysicalAppearance::all())
            ->with('emotional_states',EmotionalState::all())
            ->with('session_ratings',SessionRating::selectable())
            ->with('reports', $request->user()->reports()->orderBy('created_at','desc')->get() );
    }

    public function calendar(Request $request) {
        $allowableMentees = Mentee::canSee()->get();

        $calendar = $this->createCalendar($allowableMentees);

        return view('schedule.calendar')->with('calendar', $calendar);
    }

    /**
     *
     * Show the Expense Claim Form
     * @param Request $request
     * @return $this
     */
    public function newExpenseClaim(Request $request) {
        return view('mentor.expense-claim')
            ->with('reports', $request->user()->reports()->orderBy('created_at','desc')->get() )
            ->with('claims', $request->user()->expense_claims()->orderBy('created_at','desc')->get() );
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll() {
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

    private function createCalendar($mentees) {
        $events = $mentees->flatmap(function($mentee) {
            return $mentee->schedules()->map(function($schedule) use(&$mentee) {
                return \Calendar::event(
                    is_null($mentee->mentor()->first()) ? 'NO MENTOR' : $mentee->mentor()->first()->name,
                    true,
                    new \DateTime($schedule['next_session_date']),
                    new \DateTime($schedule['next_session_date']),
                    $schedule->id,
                    ['url' => 'schedule/' . $schedule->id]
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
