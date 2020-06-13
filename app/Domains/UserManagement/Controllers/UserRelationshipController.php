<?php

namespace App\Domains\UserManagement\Controllers;

use App\Domains\UserManagement\Models\Mentee;
use App\Domains\UserManagement\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRelationshipController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function assignMenteeToMentor(Request $request, $mentorId, $menteeId){
        $mentee = Mentee::find($menteeId);
        $mentor = User::mentor()->find($mentorId);

        if (!$mentee) {
            return redirect()->back()->withErrors('Mentee not found');
        }
        if (!$mentor) {
            return redirect()->back()->withErrors('Mentor not found');
        }

        $mentee->mentor_id = $mentor->id;
        $mentee->save();

        return redirect()->back()->with('status', $mentee->name.' is now mentored by '.$mentor->name);
    }

    public function assignMentorToManager(Request $request, $managerId, $mentorId){
        $manager = User::managerRole()->find($managerId);
        $mentor = User::mentor()->find($mentorId);

        if (!$manager) {
            return redirect()->back()->withErrors('Manager not found');
        }
        if (!$mentor) {
            return redirect()->back()->withErrors('Mentor not found');
        }

        $mentor->manager()->associate($manager);
        $mentor->save();

        return redirect()->back()->with('status', $mentor->name.' is now managed by '.$manager->name );
    }

    public function unassignMenteeFromMentor(Request $request, $mentorId, $menteeId){
        $mentor = User::mentor()->find($mentorId);
        $mentee = Mentee::find($menteeId);

        if (!$mentor) {
            return redirect()->back()->withErrors('Mentor not found');
        }
        if (!$mentee) {
            return redirect()->back()->withErrors('Mentee not found');
        }
        if ($mentee->mentor_id != $mentor->id) {
            return redirect()->back()->withErrors('Mentee not assigned to mentor');
        }

        $mentee->mentor_id = NULL;
        $mentee->save();

        return redirect()->back()->with('status', $mentee->name.' is not no longer mentored by '.$mentor->name);
    }
}
