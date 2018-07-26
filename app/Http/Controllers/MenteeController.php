<?php

namespace App\Http\Controllers;

use App\Mentee;
use App\User;
use Illuminate\Http\Request;

class MenteeController extends Controller
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
        return view('mentee.index')
            ->with('mentees', Mentee::withTrashed()->get())
            ->with('users',User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/mentee');
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
            'first_name' => 'required|string',
            'last_name' => 'required|string'
        ]);

        $mentee = new Mentee();
        $mentee->first_name = $request->first_name;
        $mentee->last_name = $request->last_name;
        $mentee->save();

        return redirect('/mentee')->with('status','Mentee Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/mentee');
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
    public function destroy(Request $request, $id)
    {

        if($request->really_delete){

            $mentee = Mentee::withTrashed()->withCount('reports')->where('id',$id)->first();
            if( $mentee->reports_count > 0 ){
                return redirect('/mentee')->with('status','Mentee cannot be deleted.');
            }

            Mentee::withTrashed()->where('id',$id)->forceDelete();
            return redirect('/mentee')->with('status','Mentee Deleted');

        }else{
            $mentee = Mentee::find($id);
            $mentee->delete();
            return redirect('/mentee')->with('status','Mentee Deactivated');
        }


    }

    /**
     * Restore a deleted mentee.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($id)
    {
        Mentee::withTrashed()
            ->where('id', $id)
            ->restore();

        return redirect('/mentee')->with('status','Mentee Restored');
    }
}
