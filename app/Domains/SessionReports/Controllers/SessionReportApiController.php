<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\SessionReports\Models\SafeguardingConcernLookup;
use App\Domains\SessionReports\Services\SessionReportService;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\SessionReports\Models\SessionSearch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SessionReportApiController extends Controller {

    private SessionReportService $sessionReportService;

    public function __construct(SessionReportService $sessionReportService) {
        $this->sessionReportService = $sessionReportService;
        
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('get', 'getById', 'export');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('update', 'delete');
    }

    public function get(Request $request) {
        // validate    
        $validator = $this->queryValidator($request->all());    
        if ($validator->fails()) {
            return $this->handleError($validator);
        }
       
        // Run search
        $search = $this->buildSearchParameters($request);
        $reports = $this->sessionReportService->getReportsUsing($search);
 
        // Construct response as JSON
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

    public function export(Request $request) {
        // validate    
        $validator = $this->queryValidator($request->all());    
        if ($validator->fails()) {
            return $this->handleError($validator);
        }
        
        // Run search
        $search = $this->buildSearchParameters($request);
        $reports = $this->sessionReportService->getReportsUsing($search);

        // Construct response as CSV
        $csvExporter = new \Laracsv\Export();
        $csvExporter->beforeEach(function ($report) {
            $report->safeguarding_text = $report->safeguardingConcernTextAttribute();
            $report->session_date_formatted = $report->session_date->format('d-m-Y');
            $report->meeting_details_escaped = str_replace(array("\r\n", "\n", "\r"), ' ', $report->meeting_details);
        });
        $csvExporter->build($reports, 
            ['id' => 'Session ID', 
            'mentor.name' => 'Mentor', 
            'mentee.name' => 'Mentee', 
            'session_date_formatted' => 'Session Date', 
            'length_of_session' => 'Length of Session',
            'activity_type.name' => 'Activity Type', 
            'location' => 'Location', 
            'safeguarding_text' => 'Safeguarding Concern', 
            'emotional_state.name' => 'Emotional State',
            'meeting_details_escaped' => 'Meeting Details']
        )->download('session-reports.csv');
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

    private function buildSearchParameters(Request $request) {
        return (new SessionSearch())
            ->mentorId($request->mentor_id)
            ->menteeId($request->mentee_id)
            ->safeguardingId($request->safeguarding_id)
            ->sessionRatingId($request->session_rating_id)
            ->sessionDateRangeStart($request->session_date_range_start)
            ->sessionDateRangeEnd($request->session_date_range_end);
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
            'safeguarding_concern' => SafeguardingConcernLookup::$values[$report->safeguarding_concern],
            'physical_appearance' => $report->physical_appearance,
            'emotional_state' => $report->emotional_state,
            'rating' => $report->session_rating,
            'meeting_details' => $report->meeting_details
        ];
    }

    private function queryValidator($params) {
        return Validator::make(
            $params,  
            [
                'session_date_range_end' => 'date|after_or_equal:session_date_range_start',
            ], 
            [
                'session_date_range_end.after_or_equal' => 'The end date should be after or equal to the start date',
            ]
        );  
    }
    
}
