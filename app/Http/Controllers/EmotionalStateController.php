<?php

namespace App\Http\Controllers;

use App\EmotionalState;
use Illuminate\Http\Request;

class EmotionalStateController extends Controller
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
        return view('emotional_state.index')
            ->with('emotional_states', EmotionalState::withTrashed()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/emotional-state');
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

        $emotional_state = new EmotionalState();
        $emotional_state->name = $request->name;
        $emotional_state->save();

        return redirect('/emotional-state')->with('status','Emotional State Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/emotional-state');
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
        $emotional_state = EmotionalState::find($id);
        $emotional_state->delete();
        return redirect('/emotional-state')->with('status','Emotional State Deactivated');
    }


    /**
     * Restore a deleted emotional state.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($id)
    {
        EmotionalState::withTrashed()
            ->where('id', $id)
            ->restore();

        return redirect('/emotional-state')->with('status','Emotional State Restored');
    }

}
