<?php

namespace App\Http\Controllers;

use App\ActivityType;
use App\ExpenseClaim;
use App\Mail\ReportSubmittedToManager;
use App\Mail\ReportSubmittedToMentor;
use App\Mentee;
use App\Report;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ScheduleController;

use Debugbar;

class SessionReportController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('mentor')->only('store');
        $this->middleware('hasAnyOfRoles:admin,mentor,manager')->only('index', 'show', 'export');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $reports = [];
        if (Auth::user()->isAdmin()) {
            $reports = Report::orderBy('id','desc')->get();
        } else if (Auth::user()->isManager()) {
            $ids = Auth::user()->assignedMentors->map(function($user) { return $user->id; });
            $reports = Report::orderBy('id', 'desc')->whereIn('mentor_id', $ids)->get();
        } else if (Auth::user()->isMentor()) {
            $reports = Report::orderBy('id', 'desc')->whereMentorId($request->user()->id)->get();
        }

        return view('session_report.index')->with('reports',  $reports);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $messages = ['rating_id.min' => 'The session rating field is required.'];
        $this->validate($request, [
            'mentee_id' => 'required|exists:mentees,id',
            'session_date' => 'required|date|before_or_equal:today',
            'rating_id' => 'required|exists:session_ratings,id|numeric|min:2',
            'length_of_session' => 'required|numeric|max:24',
            'activity_type_id' => 'required|exists:activity_types,id',
            'location' => 'required',
            'safeguarding_concern' => 'required|boolean',
            'physical_appearance_id' => 'required|exists:physical_appearances,id',
            'emotional_state_id' => 'required|exists:emotional_states,id',
            'meeting_details' => 'required',
            'next_session_date' => 'required',
            'next_session_location' => 'required'
        ], $messages);

        $report = new Report();
        $report->mentor_id = $request->user()->id;
        $report->mentee_id = $request->mentee_id;
        $report->session_date = Carbon::createFromFormat('m/d/Y',$request->session_date)->format('Y-m-d H:i:s');
        $report->rating_id = $request->rating_id;
        $report->length_of_session = $request->length_of_session;
        $report->activity_type_id = $request->activity_type_id;
        $report->location = $request->location;
        $report->safeguarding_concern = $request->safeguarding_concern;
        $report->physical_appearance_id = $request->physical_appearance_id;
        $report->emotional_state_id = $request->emotional_state_id;
        $report->meeting_details = $request->meeting_details;
        $report->save();

        $this->saveSchedule($request);

        // Send the Mentor an Email
        Mail::to($report->mentor)->send(new ReportSubmittedToMentor($report));

        // Send the Assigned Manager if any an Email
        if($report->mentor->manager){
            Mail::to($report->mentor->manager)->send(new ReportSubmittedToManager($report));
        }

        return redirect('/report')->with('status','Report Submitted');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $report = Report::find($id);
        if (!$report) {
            abort(404);
        }

        if (!Auth::user()) {
            abort(401,'Unauthorized');
        }

        $user = Auth::user();
        $report_mentor = $report->mentor()->first();
        $report_mentor_manager = $report_mentor->manager()->first();

        if ($user == $report_mentor
             || ($user ->isManager() && $user == $report_mentor_manager)
             || $user ->isAdmin() ) {
            return view('session_report.show')->with('report', $report);
        } else {
            abort(401,'Unauthorized');
        }
    }

    public function export(){
        if (!Auth::user()) {
            abort(401,'Unauthorized');
        }

        if (Auth::user()->isAdmin()) {
            $reports = Report::orderBy('id','desc')->get();
        } else if (Auth::user()->isManager()) {
            $ids = Auth::user()->assignedMentors->map(function($user) { return $user->id; });
            $reports = Report::orderBy('id', 'desc')->whereIn('mentor_id', $ids)->get();
        } else {
            $reports = Report::orderBy('id', 'desc')->whereMentorId(Auth::user()->id)->get();
        }

        return view('session_report.export')->with('reports', $reports);
    }

    private function saveSchedule(Request $request) {
        $schedule = new Schedule();
        $schedule->mentee_id = $request->mentee_id;
        $schedule->next_session_date = Carbon::createFromFormat('m/d/Y',$request->next_session_date);
        $schedule->next_session_location = $request->next_session_location;
        $schedule->save();
    }

}
