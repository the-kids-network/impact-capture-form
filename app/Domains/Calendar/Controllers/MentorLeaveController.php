<?php

namespace App\Domains\Calendar\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Calendar\Services\MentorLeaveService;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\User;
use Illuminate\Http\Request;

class MentorLeaveController extends Controller {

    private $mentorLeaveService;

    public function __construct(MentorLeaveService $mentorLeaveService) {
        $this->mentorLeaveService = $mentorLeaveService;

        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function newLeave(Request $request) {
        return view('calendar.events.mentor_leave.new')
            ->with('mentors', User::mentor()->canSee()->get());
    }

    public function getOne($id) {
        $mentorLeave = null;
        try {
            $mentorLeave = $this->mentorLeaveService->getMentorLeave($id);
        } catch (NotFoundException $e) {
            abort(401, 'Unauthorized'); 
        }

        return view('calendar.events.mentor_leave.show', compact('mentorLeave'))
            ->with('mentorLeave', $mentorLeave);
    }

    public function delete($id) {
        try {
            $this->mentorLeaveService->deleteMentorLeave($id);
        } catch (NotFoundException $e) {
            abort(401, 'Unauthorized'); 
        }

        return redirect('/calendar');
    }

    public function create(Request $request) {  
        $this->validateMentorLeave($request);  

        $leave = null;
        try {
            $leave = $this->mentorLeaveService->createMentorLeave($request->all());
        } catch (NotFoundException | NotAuthorisedException $e) {
            abort(401, 'Unauthorized'); 
        }

        return redirect('/mentor/leave/'.$leave->id)
                ->with('status', "Leave Created");
    }

    public function update(Request $request, $id) {  
        $this->validateMentorLeave($request);  

        $leave = null;
        try {
            $leave = $this->mentorLeaveService->updateMentorLeave($id, $request->all());
        } catch (NotFoundException | NotAuthorisedException $e) {
            abort(401, 'Unauthorized'); 
        }

        return redirect('/mentor/leave/'.$leave->id)
            ->with('status', "Leave Updated");
    }

    private function validateMentorLeave(Request $request) {
        $request->validate(
            [
                'mentor_id' => 'required|exists:users,id',
                'start_date' => 'required|date|date_format:d-m-Y|before_or_equal:end_date',
                'end_date' => 'required|date|date_format:d-m-Y',
                'description' => 'nullable|string|max:50'
            ],
            [
                'start_date.before_or_equal' => 'The start date should be before or equal to the end date.',
            ]
        );   
    }
}
