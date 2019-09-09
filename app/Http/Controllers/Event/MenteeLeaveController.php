<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\MenteeLeave;
use App\Mentee;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MenteeLeaveController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function create(Request $request) {
        return view('mentee_leave.new')
            ->with('mentees', Mentee::canSee()->get());
    }

    public function show($id) {
        $menteeLeave = MenteeLeave::canSee()->find($id);
        if (!$menteeLeave) {
            abort(401, 'Unauthorized'); 
        }

        return view('mentee_leave.show', compact('menteeLeave'))
            ->with('menteeLeave', $menteeLeave);
    }

    public function destroy($id) {
        $allowable = MenteeLeave::canSee();
        $toDelete = $allowable->find($id);
        if (!$toDelete) {
            abort(401, 'Unauthorized'); 
        }

        MenteeLeave::destroy($toDelete->id);

        return redirect('/calendar');
    }

    public function store(Request $request) {  
        $request->validate(
            [
                'mentee_id' => 'required|exists:mentees,id',
                'start_date' => 'required|date|date_format:d-m-Y|before_or_equal:end_date',
                'end_date' => 'required|date|date_format:d-m-Y',
                'description' => 'nullable|string|max:50'
            ],
            [
                'start_date.before_or_equal' => 'The start date should be before or equal to the end date.',
            ]
        );    

        if (!Mentee::canSee()->find($request->mentee_id)) {
            abort(401, 'Unauthorized'); 
        }

        $leave = MenteeLeave::canSee()->find($request->id);
        if (!$leave) {
            $leave = new MenteeLeave();
        }

        $leave->mentee_id = $request->mentee_id;
        $leave->start_date = Carbon::createFromFormat('d-m-Y',$request->start_date)->setTime(0,0,0);
        $leave->end_date = Carbon::createFromFormat('d-m-Y',$request->end_date)->setTime(0,0,0);
        $leave->description = $request->description;
        $leave->save();

        return redirect('/calendar');
    }
}
