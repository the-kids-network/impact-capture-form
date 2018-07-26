<?php

namespace App\Http\Controllers;

use App\ActivityType;
use App\ExpenseClaim;
use App\Mail\ReportSubmittedToManager;
use App\Mail\ReportSubmittedToMentor;
use App\Mentee;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dev')->except('store','show');
        $this->middleware('manager')->only('show');
        $this->middleware('mentorOnly')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('report.index')->with('reports', Report::orderBy('id','desc')->get() );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'mentee_id' => 'required|exists:mentees,id',
            'session_date' => 'required|date|before_or_equal:today',
            'length_of_session' => 'required|numeric|max:24',
            'activity_type_id' => 'required|exists:activity_types,id',
            'location' => 'required',
            'safeguarding_concern' => 'required|boolean',
            'physical_appearance_id' => 'required|exists:physical_appearances,id',
            'emotional_state_id' => 'required|exists:emotional_states,id',
            'meeting_details' => 'required'
        ]);

        $report = new Report();
        $report->mentor_id = $request->user()->id;
        $report->mentee_id = $request->mentee_id;
        $report->session_date = Carbon::createFromFormat('m/d/Y',$request->session_date)->format('Y-m-d H:i:s');
        $report->length_of_session = $request->length_of_session;
        $report->activity_type_id = $request->activity_type_id;
        $report->location = $request->location;
        $report->safeguarding_concern = $request->safeguarding_concern;
        $report->physical_appearance_id = $request->physical_appearance_id;
        $report->emotional_state_id = $request->emotional_state_id;
        $report->meeting_details = $request->meeting_details;
        $report->save();

        // Send the Mentor an Email
        Mail::to($report->mentor)->send(new ReportSubmittedToMentor($report));

        // Send the Assigned Manager if any an Email
        if($report->mentor->manager){
            Mail::to($report->mentor->manager)->send(new ReportSubmittedToManager($report));
        }

        return redirect('/my-reports')->with('status','Report Submitted');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('report.show')->with('report',Report::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function export(){
        return view('report.export')
            ->with('reports',Report::orderBy('id','desc')->get());
    }


}
