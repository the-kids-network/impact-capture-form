<?php

namespace App\Http\Controllers;

use App\PhysicalAppearance;
use Illuminate\Http\Request;

class PhysicalAppearanceController extends Controller
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
        return view('physical_appearance.index')
            ->with('physical_appearances', PhysicalAppearance::withTrashed()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/physical-appearance');
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

        $physical_appearance = new PhysicalAppearance();
        $physical_appearance->name = $request->name;
        $physical_appearance->save();

        return redirect('/physical-appearance')->with('status','Physical Appearance Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/physical-appearance');
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
        $physical_appearance = PhysicalAppearance::find($id);
        $physical_appearance->delete();
        return redirect('/physical-appearance')->with('status','Physical Appearance Deactivated');
    }


    /**
     * Restore a deleted physical appearance.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restore($id)
    {
        PhysicalAppearance::withTrashed()
            ->where('id', $id)
            ->restore();

        return redirect('/physical-appearance')->with('status','Physical Appearance Restored');
    }

}
