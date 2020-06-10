<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\SessionReports\Models\SafeguardingConcernLookup;
use App\Domains\SessionReports\Services\SessionReportService;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\SessionReports\Models\SessionSearch;
use App\Domains\UserManagement\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SessionReportApiController extends Controller {

    private SessionReportService $sessionReportService;

    public function __construct(SessionReportService $sessionReportService) {
        $this->sessionReportService = $sessionReportService;
        
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get', 'getById');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('update', 'delete');
    }

    public function index(Request $request) {
        $mentors = User::mentor()->canSee()->get();

        $mentorsResponse = $mentors->map(fn($mentor) => [
            'id' => $mentor->id,
            'name' => $mentor->name,
            'mentees' => $mentor->mentees->map(fn($mentee) => [
                'id' => $mentee->id,
                'name' => $mentee->name
            ])
        ]);
    
        return view('session_reports.beta')->with('mentors', $mentorsResponse);;
    }

    public function get(Request $request) {
        $search = (new SessionSearch())
            ->mentorId($request->mentor_id)
            ->menteeId($request->mentee_id)
            ->sessionDateRangeStart($request->session_date_range_start)
            ->sessionDateRangeEnd($request->session_date_range_end);

        // Run search
        $reports = $this->sessionReportService->getReportsUsing($search);
 
        // Construct response
        $payload = $reports->map(function($report) use($request) {
            $reportDto = $this->mapReportToDto($report);

            foreach ($request->exclude_fields as $fieldToExlude) {
                unset($reportDto[$fieldToExlude]); 
            }

            return $reportDto;
        });

        // return update report
        return response()->json($payload);
    }

    public function getById($id) {
        $report = null;
        try {
            $report = $this->sessionReportService->getReport($id);
        } catch (NotFoundException | NotAuthorisedException $e) {
            Log::error($e);
            abort(401,'Unauthorized');
        }

        return response()->json($this->mapReportToDto($report));
    }

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
            Log::error($e);
            abort(401,'Unauthorized');
        }

        // return update report
        return response()->json($this->mapReportToDto($report));
    }

    public function delete($id) {
        try {
            $this->sessionReportService->deleteReport($id);
        } catch (NotFoundException $e) {
            Log::error($e);
            abort(401,'Unauthorized');
        }

        return response()->json([
            'id' => $id,
        ]);
    }

    private function mapReportToDto($report) {
        return [
            'id' => $report->id,
            'mentor' => [
                'id' => $report->mentor->id,
                'name' => $report->mentor->name
            ],
            'mentee' => [
                'id' => $report->mentee->id,
                'name' => $report->mentee->name
            ],
            'session_date' => $report->session_date,
            'length_of_session' => $report->length_of_session,
            'activity_type' => $report->activity_type,
            'location' => $report->location,
            'safeguarding_concern' => [
                'id' => $report->safeguarding_concern,
                'type' => SafeguardingConcernLookup::$values[$report->safeguarding_concern]['name']
            ],
            'physical_appearance' => $report->physical_appearance,
            'emotional_state' => $report->emotional_state,
            'rating' => $report->session_rating,
            'meeting_details' => $report->meeting_details
        ];
    }
    
}
