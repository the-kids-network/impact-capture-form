<?php

namespace App\Http\Controllers;

use App\Domains\SessionReports\Services\SessionReportService;
use App\Mentee;
use App\User;
use Illuminate\Http\Request;

class MenteeController extends Controller {

    private $sessionReportService;

    public function __construct(SessionReportService $sessionReportService) {
        $this->sessionReportService = $sessionReportService;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('user_management.mentees.index')
            ->with('allMentees', Mentee::withTrashed()->get())
            ->with('assignableMentees', Mentee::all())
            ->with('assignableMentors', User::mentor()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return redirect('/mentee');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
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
    public function show($id) {
        return redirect('/mentee');
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if ($request->really_delete){
            $mentee = Mentee::withTrashed()->where('id',$id)->first();

            $menteeHasSessionReports = $this->sessionReportService->menteeHasReports($id);
            if ($menteeHasSessionReports) {
                return redirect('/mentee')->with('status','Mentee cannot be deleted as session reports associated with mentee');
            }

            Mentee::withTrashed()->where('id',$id)->forceDelete();
            return redirect('/mentee')->with('status','Mentee Deleted');

        } else {
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
    public function restore($id) {
        Mentee::withTrashed()
            ->where('id', $id)
            ->restore();

        return redirect('/mentee')->with('status','Mentee Restored');
    }
}
