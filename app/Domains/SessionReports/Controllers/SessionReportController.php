<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\Calendar\Services\MenteeLeaveService;
use App\Domains\Calendar\Services\MentorLeaveService;
use App\Domains\Calendar\Services\PlannedSessionService;
use App\Domains\SessionReports\Models\ActivityType;
use App\Domains\SessionReports\Models\EmotionalState;
use App\Domains\SessionReports\Models\Report;
use App\Domains\SessionReports\Models\SessionRating;
use App\Domains\SessionReports\Services\SessionReportService;
use App\Exceptions\DuplicateException;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\ExpenseClaim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\SessionReports\Controllers\SessionReportValidation;
use Illuminate\Support\Facades\Validator;

class SessionReportController extends Controller {

    private $sessionReportService;
    private $plannedSessionService;
    private $mentorLeaveService;
    private $menteeLeaveService;

    public function __construct(SessionReportService $sessionReportService, 
                                PlannedSessionService $plannedSessionService,
                                MentorLeaveService $mentorLeaveService,
                                MenteeLeaveService $menteeLeaveService) {
        $this->sessionReportService = $sessionReportService;
        $this->plannedSessionService = $plannedSessionService;
        $this->mentorLeaveService = $mentorLeaveService;
        $this->menteeLeaveService = $menteeLeaveService;
        
        $this->middleware('auth');
        $this->middleware('mentor')->only('newReportForm', 'create');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('getMany', 'getOne', 'export');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('editReportForm', 'update', 'delete');
    }

    public function newReportForm(Request $request) {
        $reports = $this->sessionReportService->getReports();

        return view('session_report.new')
            ->with('mentees',$request->user()->mentees)
            ->with('activity_types', ActivityType::all())
            ->with('emotional_states',EmotionalState::all())
            ->with('session_ratings',SessionRating::selectable())
            ->with('reports', $reports);
    }

    public function editReportForm(Request $request, $id) {
        $report = $this->sessionReportService->getReport($id);
        $report->mentee = $report->mentee;
        
        return view('session_report.edit')
            ->with('activity_types',  ActivityType::all())
            ->with('emotional_states', EmotionalState::all())
            ->with('session_ratings', SessionRating::selectable())
            ->with('report', $report);
    }

    public function getMany(Request $request) {
        $reports = [];
        if ($request->mentor_id) {
            $reports = $this->sessionReportService->getReportsForMentor($request->mentor_id);
        } else {
            $reports = $this->sessionReportService->getReports();
        }

        return view('session_report.index')->with('reports',  $reports);
    }

    public function getOne($id) {
        $report = null;
        try {
            $report = $this->sessionReportService->getReport($id);
        } catch (NotFoundException | NotAuthorisedException $e) {
            abort(401,'Unauthorized');
        }

        // Eventually should be in expenses domain in a service class
        // and ideally the UI could fetch these separately via a REST call to the expenses domain
        $claims = ExpenseClaim::canSee()->whereReportId($report->id)->orderBy('created_at','desc')->get();

        return view('session_report.show')
            ->with('report', $report)
            ->with('claims', $claims);
    }

    public function create(Request $request) {
        list($validations, $messages) = SessionReportValidation::getRulesFor(['users', 'session_report', 'planned_session', 'leave']);
        $this->validate($request, 
            $validations,
            $messages
        );

        // Save session report
        try {
            $this->sessionReportService->createReport($request->all());
        } catch (NotAuthorisedException $e) {
            abort(401,'Unauthorized');
        }

        // save other things
        $this->createPlannedSession($request);
        $this->createLeave($request);

        return redirect('/report')->with('status', 'Report Submitted');
    }

    // The only REST endpoint right now
    public function update(Request $request, $id) {
        list($validations, $messages) = SessionReportValidation::getRulesFor(['users', 'session_report']);
        $bodyJson = $request->json()->all();
        $validator = Validator::make($bodyJson, $validations, $messages);
        if ($validator->fails()) {
            return $this->handleError($validator);
        }
        
        // Update the session report
        $report = null;
        try {
            $report = $this->sessionReportService->updateReport($id, $request->all());
        } catch (NotAuthorisedException $e) {
            abort(401,'Unauthorized');
        }

        // return update report
        return response()->json($report);
    }

    public function delete($id) {
        try {
            $this->sessionReportService->deleteReport($id);
        } catch (NotFoundException $e) {
            abort(401,'Unauthorized');
        }

        return redirect('/report')->with('status', 'Report Deleted');
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

    private function createPlannedSession(Request $request) {
        try {
            $this->plannedSessionService->createPlannedSession(
                $request->mentee_id,
                $request->next_session_date,
                $request->next_session_location);
        } catch (DuplicateException $e) {
            // If duplicate, ignore i.e. don't fail
        }
    }

    private function createLeave(Request $request) {
        if (isset($request->leave_type) && 
            isset($request->leave_start_date) && 
            isset($request->leave_end_date)) {

            $params = [
                'mentor_id' => $request->mentor_id,
                'mentee_id' => $request->mentee_id,
                'start_date' => $request->leave_start_date,
                'end_date' => $request->leave_end_date,
                'description' => $request->leave_description
            ];

            if ($request->leave_type = 'mentee') {
                $this->menteeLeaveService->createMenteeLeave($params);
            } else {
                $this->mentorLeaveService->createMentorLeave($params);
            }
        }
    }
}
