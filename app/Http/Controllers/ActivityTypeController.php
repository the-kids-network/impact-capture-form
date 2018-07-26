<?php

namespace App\Http\Controllers;

use App\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware('dev');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('activity_type.index')->with('activity_types', ActivityType::withTrashed()->get() );
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
            'name' => 'required|string'
        ]);

        $activity_type = new ActivityType();
        $activity_type->name = $request->name;
        $activity_type->save();

        return redirect('activity-type')->with('status','Activity Type Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
