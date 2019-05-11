<?php

namespace App\Http\Controllers;

use App\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MentorReportingController extends Controller
{
    private const REQUEST_DATE_FORMAT = 'd-m-Y';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager');
    }

    /**
     * Display the reporting landing page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reporting.mentor.index');
    }

    /**
     * Export the report as CSV
     */
    public function generateExportableReport(Request $request) 
    {
        return $this->generate($request, 'reporting.mentor.export');
    }
    
    /**
     * Generate the report from parameters supplied
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateIndexReport(Request $request)
    {   
        return $this->generate($request, 'reporting.mentor.index');
    }

    public function generate(Request $request, $view_name)
    {   
        // Validate date parameters
        $this->validate_parameters($request);

        // Default dates if necessary and redirect back
        $report_start_date = ($request->start_date) ? Carbon::createFromFormat(self::REQUEST_DATE_FORMAT, $request->start_date) 
                                                    : Carbon::createFromTimestamp(0, 'Europe/London');
        $report_end_date = ($request->end_date) ? Carbon::createFromFormat(self::REQUEST_DATE_FORMAT, $request->end_date) 
                                                : Carbon::now('Europe/London');                                            
        if (!$request->start_date || !$request->end_date) {
            return redirect()->route('mentor-reporting-index', 
                                    ['start_date' => $report_start_date->format(self::REQUEST_DATE_FORMAT),
                                     'end_date' => $report_end_date->format(self::REQUEST_DATE_FORMAT)]);
        }

        // Generate stats data
        $user = Auth::user();
        $manager_id = ($user && $user->isManager()) ? Auth::id() : null;
        $mentors = $this->get_stats($report_start_date, $report_end_date, $manager_id);

        // Display results
        return view($view_name)->with('mentors', $mentors);
    }

    private function get_stats($report_start_date, $report_end_date, $manager_id) {
        $mentors_stats = DB::select("
                SELECT DISTINCT 
                    u.id as mentor_id, 
                    u.name as mentor_name,
                    m.name as manager_name,
                    f.first_session_date,
                    COALESCE(SUM(s.session_count), 0) AS session_count, 
                    COALESCE(SUM(s.session_length), 0) AS session_length, 
                    COALESCE(SUM(s.expenses_total), 0) AS expenses_total, 
                    COALESCE(SUM(s.expenses_pending), 0) AS expenses_pending,
                    COALESCE(SUM(s.expenses_approved), 0) AS expenses_approved,
                    COALESCE(SUM(s.expenses_rejected), 0) AS expenses_rejected
                FROM users u
                LEFT JOIN users m ON m.id = u.manager_id
                LEFT JOIN (
                    /* Join first session date */
                    SELECT DISTINCT
                        user_id,
                        MIN(session_date) AS first_session_date
                    FROM reporting_sessions
                    GROUP BY user_id
                ) f ON f.user_id = u.id
                LEFT JOIN (
                    /* Join sessions */
                    SELECT DISTINCT * 
                    FROM reporting_sessions
                    WHERE 1=1
                    AND session_date >= :start_date
                    AND session_date <= :end_date
                ) s ON s.user_id = u.id
                WHERE 1=1
                ".
                ( $manager_id ? 'AND u.manager_id = '.$manager_id : '' )
                ."
                AND u.role IS NULL
                AND u.role IS NULL
                AND u.deleted_at IS NULL  
                GROUP BY u.id, u.name, f.first_session_date;", 
                [ 
                    'start_date' => $report_start_date->format('Y-m-d'), 
                    'end_date' => $report_end_date->format('Y-m-d')
                ]);

        $mentors_stats = $this->add_expected_session_count($mentors_stats, $report_start_date, $report_end_date);

        return $mentors_stats;
    }

    /**
     * Calculates expected session count per mentor based on their start date.
     * The expectation is that a mentor has one session per week.
     * This mutates the supplied stats data structure - not ideal.
     */
    private function add_expected_session_count($mentors_stats, $report_start_date, $report_end_date) {
        
        // pure function
        $expected_sessions_func = function ($first_session_date, $report_start_date, $report_end_date) { 
            if (!$first_session_date) return null;

            $first_session_date = Carbon::createFromFormat('Y-m-d', $first_session_date);

            if ($first_session_date->between($report_start_date, $report_end_date, true)) {
                return $report_end_date->diffInWeeks($first_session_date);
            } 
            else if ($first_session_date > $report_end_date) {
                return 0;
            }
            else {
                return $report_end_date->diffInWeeks($report_start_date);
            }
        };

        // mutating code
        foreach ($mentors_stats as $mentor) {
            $mentor->expected_session_count = $expected_sessions_func(
                $mentor->first_session_date, 
                $report_start_date, 
                $report_end_date
            );
        }

        return $mentors_stats;
    }

    /**
     * Validates the supplied query parameters in the request
     */
    private function validate_parameters(Request $request) {
        $messages = [
            'start_date.before_or_equal' => 'The start date should be before or equal to the end date',
            'end_date.before_or_equal' => 'The end date should not be in the future'
        ];
        $request->validate([
            'start_date' => 'date|before_or_equal:end_date',
            'end_date' => 'date|before_or_equal:today',
        ], $messages);
    }

}
