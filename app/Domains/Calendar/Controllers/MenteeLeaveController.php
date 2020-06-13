<?php

namespace App\Domains\Calendar\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Calendar\Services\MenteeLeaveService;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Domains\UserManagement\Models\Mentee;
use Illuminate\Http\Request;

class MenteeLeaveController extends Controller {

    private $menteeLeaveService;

    public function __construct(MenteeLeaveService $menteeLeaveService) {
        $this->menteeLeaveService = $menteeLeaveService;
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor');
    }

    public function newLeave() {
        return view('calendar.events.mentee_leave.new')
            ->with('mentees', Mentee::canSee()->get());
    }

    public function getOne($id) {
        $menteeLeave = null;
        try {
            $menteeLeave = $this->menteeLeaveService->getMenteeLeave($id);
        } catch (NotFoundException $e) {
            abort(401, 'Unauthorized'); 
        }

        return view('calendar.events.mentee_leave.show', compact('menteeLeave'))
            ->with('menteeLeave', $menteeLeave);
    }

    public function delete($id) {
        try {
            $this->menteeLeaveService->deleteMenteeLeave($id);
        } catch (NotFoundException $e) {
            abort(401, 'Unauthorized'); 
        }

        return redirect('/calendar');
    }

    public function create(Request $request) {  
        $this->validateMenteeLeave($request); 

        $leave = null;
        try {
            $leave = $this->menteeLeaveService->createMenteeLeave($request->all());
        } catch (NotFoundException | NotAuthorisedException $e) {
            abort(401, 'Unauthorized'); 
        }

        return redirect('/mentee/leave/'.$leave->id)
                ->with('status', "Leave Created");  
    }

    public function update(Request $request, $id) {  
        $this->validateMenteeLeave($request);

        $leave = null;
        try {
            $leave = $this->menteeLeaveService->updateMenteeLeave($id, $request->all());
        } catch (NotFoundException | NotAuthorisedException $e) {
            abort(401, 'Unauthorized'); 
        }

        return redirect('/mentee/leave/'.$leave->id)
                ->with('status', "Leave Updated");
    }

    private function validateMenteeLeave(Request $request) {
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
    }

}
