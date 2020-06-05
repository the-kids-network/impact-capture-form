<?php

namespace App\Domains\SessionReports\Controllers;

use App\Domains\SessionReports\Models\ActivityType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('session_report.activity_type.index')->with('activity_types', ActivityType::withTrashed()->get() );
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
            'name' => 'required|string'
        ]);

        $activity_type = new ActivityType();
        $activity_type->name = $request->name;
        $activity_type->save();

        return redirect('activity-type')->with('status','Activity Type Added');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity_type = ActivityType::find($id);
        $activity_type->delete();
        return redirect('/activity-type')->with('status','Activity Type Deactivated');
    }

    public function restore($id){
        ActivityType::withTrashed()
            ->where('id', $id)
            ->restore();

        return redirect('/activity-type')->with('status','Activity Type Restored');
    }
}
