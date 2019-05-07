<?php

namespace App\Http\Controllers;

use App\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dev');
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
     * Generate the report from parameters supplied
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {   
        if (!$request->start_date || !$request->end_date) {
            $dates = $this->date_defaulter($request);
            return redirect()->route('reporting-generate', 
                                    [
                                        'start_date' => $dates['start_date']->format('m/d/Y'),
                                        'end_date' => $dates['end_date']->format('m/d/Y')
                                    ]);
        }

        // Validate date parameters
        $messages = [
            'required' => 'The :attribute field is required.',
            'start_date.before_or_equal' => 'The start date should be before or equal to the end date',
            'end_date.before_or_equal' => 'The end date should not be in the future'
        ];
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|before_or_equal:today',
        ], $messages);

        $start_date_formatted = Carbon::createFromFormat('m/d/Y', $request->start_date)->format('Y-m-d');
        $end_date_formatted = Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d');

        $mentors_stats = DB::select('
            select 
                u.id as mentor_id, 
                u.name as mentor_name, 	
                sum(COALESCE(r.session_count, 0)) as session_count,
                sum(COALESCE(r.length_of_session, 0)) as total_hours
            from users u
            left join (
                select 
                    mentor_id,
                    mentee_id,
                    session_date,
                    1 as session_count,
                    length_of_session
                from reports
                where 1=1
                and session_date >= ?
                and session_date <= ?
            ) r on u.id=r.mentor_id
            where u.deleted_at is null
            and u.role is null
            group by u.id, u.name;
        ', [$start_date_formatted, $end_date_formatted]);

        return view('reporting.mentor.index')->with('mentors', $mentors_stats);
    }

    /**
     * Default date parameters if not supplied
     */
    private function date_defaulter(Request $request) {
        // default end date to today
        if (!$request->end_date) {
            $end_date = Carbon::now();
        } else {
            $end_date = Carbon::createFromFormat('m/d/Y',$request->end_date);
        }

        // default start date to 6 days back from end date
        if (!$request->start_date) {
            $start_date = $end_date->copy()->subDays(6);
        } else {
            $start_date = Carbon::createFromFormat('m/d/Y',$request->start_date);
        }

        return ['start_date' => $start_date, 'end_date' => $end_date];
    }

}
