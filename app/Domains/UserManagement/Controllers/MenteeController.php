<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\SessionReports\Services\SessionReportService;
use App\Domains\UserManagement\Models\Mentee;
use App\Domains\UserManagement\Models\User;
use App\Http\Controllers\Controller;
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
        return view('user_management.mentees')
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
        return redirect('/mentees');
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

        return redirect('/mentees')->with('status','Mentee Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('/mentees');
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

            // this should ideally be a rest call to the session reports domain
            // to remove the coupling
            $menteeHasSessionReports = $this->sessionReportService->menteeHasReports($id);
            if ($menteeHasSessionReports) {
                return redirect('/mentees')->withErrors('Mentee cannot be deleted as session reports associated with mentee');
            }

            Mentee::withTrashed()->where('id',$id)->forceDelete();
            return redirect('/mentees')->with('status','Mentee Deleted');

        } else {
            $mentee = Mentee::find($id);
            $mentee->delete();
            return redirect('/mentees')->with('status','Mentee Deactivated');
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

        return redirect('/mentees')->with('status','Mentee Restored');
    }
}
