<?php

namespace App\Http\Controllers;

use App\ExpenseClaim;
use App\Mail\ReportSubmittedToManager;
use App\Mail\ReportSubmittedToMentor;
use App\Mail\SafeguardingConcernAlert;
use App\Mentee;
use App\User;
use App\Report;
use App\PlannedSession;
use App\ActivityType;
use App\EmotionalState;
use App\PhysicalAppearance;
use App\SessionRating;
use App\MentorLeave;
use App\MenteeLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Debugbar;

class SessionReportController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('mentor')->only('store', 'create');
        $this->middleware('hasAnyOfRoles:admin,mentor,manager')->only('index', 'show', 'export');
    }

    public function create(Request $request) {
        return view('session_report.new')
            ->with('mentees',$request->user()->mentees)
            ->with('activity_types', ActivityType::all())
            ->with('physical_appearances',PhysicalAppearance::all())
            ->with('emotional_states',EmotionalState::all())
            ->with('session_ratings',SessionRating::selectable())
            ->with('reports', $request->user()->reports()->orderBy('created_at','desc')->get() );
    }

    public function index(Request $request) {
        // apply role permission scope
        $query = Report::canSee()->orderBy('created_at','desc');

        // apply user supplied filters
        if ($request->mentor_id) {
            $query->whereMentorId($request->mentor_id);
        }

        // get reports
        $reports = $query->get();

        return view('session_report.index')->with('reports',  $reports);
    }

    public function show($id) {
        if (!Report::find($id)) abort(404);

        $report = Report::canSee()->whereId($id)->first();
        if(!$report) abort(401,'Unauthorized');
        $claims = $report->expense_claims()->orderBy('created_at','desc')->get();

        return view('session_report.show')
            ->with('report', $report)
            ->with('claims', $claims);
    }

    public function store(Request $request) {
        $this->validate($request, 
            [
                'mentee_id' => 'required|exists:mentees,id',
                'session_date' => 'required|date|date_format:m/d/Y|before_or_equal:today',
                'rating_id' => 'required|exists:session_ratings,id|numeric|min:2',
                'length_of_session' => 'required|numeric|min:1|max:24',
                'activity_type_id' => 'required|exists:activity_types,id',
                'location' => 'required|string|max:50',
                'safeguarding_concern' => 'required|boolean',
                'physical_appearance_id' => 'required|exists:physical_appearances,id',
                'emotional_state_id' => 'required|exists:emotional_states,id',
                'meeting_details' => 'required|string|max:1000',
                'next_session_date' => 'required|date|date_format:m/d/Y|after_or_equal:today',
                'next_session_location' => 'required|string|max:50',
                'mentor_id' => 'required|exists:users,id',
                'leave_type' => "required|in:mentor,mentee",
                'leave_start_date' => 'nullable|date|date_format:m/d/Y|before_or_equal:leave_end_date',
                'leave_end_date' => 'nullable|date|date_format:m/d/Y',
                'leave_description' => 'nullable|string|max:50'
            ], 
            [
                'session_date.before_or_equal' => 'The session date should be before or equal to today.',
                'rating_id.min' => 'The session rating field is required.',
                'next_session_date.after_or_equal' => 'The next session date should be in the future.',
                'leave_start_date.before_or_equal' => 'The leave start date should be before or equal to the end date.'
            ]
        );

        // Check mentor has mentee
        $mentee = Mentee::canSee()->whereId($request->mentee_id)->first();
        if (!$mentee) abort(401,'Unauthorized');

        // Save parts
        $report = $this->saveReport($request);
        $this->saveNextPlannedSession($request);
        $this->saveLeave($request);

        // Send the Mentor an Email
        Mail::to($report->mentor)->send(new ReportSubmittedToMentor($report));

        // Send the Assigned Manager if any an Email
        if($report->mentor->manager){
            Mail::to($report->mentor->manager)->send(new ReportSubmittedToManager($report));
        }

        // Send email if safeguarding concern
        if($report->safeguarding_concern){
            $mail = ($report->mentor->manager) 
                ? Mail::to($report->mentor->manager)->cc(User::admin()->get())
                : Mail::to(User::admin()->get());

            $mail ->send(new SafeguardingConcernAlert($report));
        }

        return redirect('/report')->with('status', 'Report Submitted');
    }

    public function export(Request $request){
        $query = Report::canSee()->orderBy('created_at','desc');

        if ($request->mentor_id) {
            $query->whereMentorId($request->mentor_id);
        }

        // get reports
        $reports = $query->get();

        return view('session_report.export')->with('reports', $reports);
    }

    private function saveReport(Request $request) {
        $report = new Report();
        $report->mentor_id = $request->user()->id;
        $report->mentee_id = $request->mentee_id;
        $report->session_date = Carbon::createFromFormat('m/d/Y',$request->session_date)->setTime(0,0,0);
        $report->rating_id = $request->rating_id;
        $report->length_of_session = $request->length_of_session;
        $report->activity_type_id = $request->activity_type_id;
        $report->location = $request->location;
        $report->safeguarding_concern = $request->safeguarding_concern;
        $report->physical_appearance_id = $request->physical_appearance_id;
        $report->emotional_state_id = $request->emotional_state_id;
        $report->meeting_details = $request->meeting_details;
        $report->save();
        return $report;
    }

    private function saveNextPlannedSession(Request $request) {
        $plannedSession = new PlannedSession();
        $plannedSession->mentee_id = $request->mentee_id;
        $plannedSession->date = Carbon::createFromFormat('m/d/Y',$request->next_session_date)->setTime(0,0,0);
        $plannedSession->location = $request->next_session_location;
        $plannedSession->save();
        return $plannedSession;
    }

    private function saveLeave(Request $request) {
        if (isset($request->leave_type) && 
            isset($request->leave_start_date) && 
            isset($request->leave_end_date)) {

            if ($request->leave_type = 'mentee') {
                $leave = new MenteeLeave();
                $leave->mentee_id = $request->mentee_id;
            } else {
                $leave = new MentorLeave();
                $leave->mentor_id = $request->mentor_id;
            }

            $leave->start_date = Carbon::createFromFormat('m/d/Y',$request->leave_start_date)->setTime(0,0,0);
            $leave->end_date = Carbon::createFromFormat('m/d/Y',$request->leave_end_date)->setTime(0,0,0);
            $leave->description = $request->leave_description;
            $leave->save();
            return $leave;
        }
    }
}
