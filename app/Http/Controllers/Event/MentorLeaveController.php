<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\MentorLeave;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MentorLeaveController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function create(Request $request) {
        return view('mentor_leave.new')
            ->with('mentors', User::mentor()->canSee()->get());
    }

    public function show($id) {
        $mentorLeave = MentorLeave::canSee()->find($id);
        if (!$mentorLeave) {
            abort(401, 'Unauthorized'); 
        }

        return view('mentor_leave.show', compact('mentorLeave'))
            ->with('mentorLeave', $mentorLeave);
    }

    public function destroy($id) {
        $allowable = MentorLeave::canSee();
        $toDelete = $allowable->find($id);
        if (!$toDelete) {
            abort(401, 'Unauthorized'); 
        }

        MentorLeave::destroy($toDelete->id);

        return redirect('/calendar');
    }

    public function store(Request $request) {  
        $request->validate(
            [
                'start_date' => 'required|date|date_format:m/d/Y|before_or_equal:end_date',
                'end_date' => 'required|date|date_format:m/d/Y',
                'description' => 'nullable|string|max:50'
            ],
            [
                'start_date.before_or_equal' => 'The start date should be before or equal to the end date.',
            ]
        );    

        $leave = MentorLeave::find($request->id);
        if (!$leave) {
            $leave = new MentorLeave();
        }

        $leave->mentor_id = $request->mentor_id;
        $leave->start_date = Carbon::createFromFormat('m/d/Y',$request->start_date);
        $leave->end_date = Carbon::createFromFormat('m/d/Y',$request->end_date);
        $leave->description = $request->description;
        $leave->save();

        return redirect('/calendar');
    }
}
