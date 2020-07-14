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
use App\Domains\Expenses\Models\ExpenseClaim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\SessionReports\Controllers\SessionReportValidation;
use Illuminate\Support\Facades\Log;

class SessionReportController extends Controller {

    private SessionReportService $sessionReportService;
    private PlannedSessionService $plannedSessionService;
    private MentorLeaveService $mentorLeaveService;
    private MenteeLeaveService $menteeLeaveService;

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
    }

    public function newReportForm(Request $request) {
        $reports = $this->sessionReportService->getReports();

        return view('session_reports.new')
            ->with('mentees',$request->user()->mentees)
            ->with('activity_types', ActivityType::all())
            ->with('emotional_states', EmotionalState::all())
            ->with('session_ratings', SessionRating::selectable())
            ->with('reports', $reports);
    }

    public function create(Request $request) {
        list($validations, $messages) = SessionReportValidation::getRulesFor(['users', 'session_report', 'planned_session', 'leave']);
        $this->validate($request, 
            $validations,
            $messages
        );

        // Save session report
        $report = null;
        try {
            $report = $this->sessionReportService->createReport($request->all());
        } catch (NotAuthorisedException $e) {
            Log::error($e);
            abort(401,'Unauthorized');
        }

        // save other things
        $this->createPlannedSession($request);
        $this->createLeave($request);

        return redirect('/app#/session-reports/'.$report->id)->with('status', 'Report Created');
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
