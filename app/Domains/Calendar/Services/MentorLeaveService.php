<?php

namespace App\Domains\Calendar\Services;

use App\Domains\Calendar\Models\MentorLeave;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\User;
use Carbon\Carbon;

class MentorLeaveService {
   
    public function getMentorLeaves() {
        return MentorLeave::canSee()->get();
    }

    public function getMentorLeave($id) {
        return MentorLeave::canSee()->find($id);
    }

    public function deleteMentorLeave($id) {
        $toDelete = MentorLeave::canSee()->find($id);
        if (!$toDelete) throw new NotFoundException("Mentor leave not found");
        MentorLeave::destroy($toDelete->id);
    }

    public function createMentorLeave($keyValues) {
        if (!User::mentor()->canSee()->find($keyValues['mentor_id'])) {
            throw new NotAuthorisedException("Current user cannot manage mentor's leave"); 
        }

        $leave = new MentorLeave();
        $leave->mentor_id = $keyValues['mentor_id'];
        $leave->start_date = Carbon::createFromFormat('d-m-Y',$keyValues['start_date'])->setTime(0,0,0);
        $leave->end_date = Carbon::createFromFormat('d-m-Y',$keyValues['end_date'])->setTime(0,0,0);
        $leave->description = $keyValues['description'];
        $leave->save();
    } 

    public function updateMentorLeave($id, $keyValues) {
        if (!User::mentor()->canSee()->find($keyValues['mentor_id'])) {
            throw new NotAuthorisedException("Current user cannot manage mentor's leave"); 
        }

        $toUpdate = MentorLeave::canSee()->find($id);
        if (!$toUpdate) throw new NotFoundException("Mentor leave not found");

        $toUpdate->mentor_id = $keyValues['mentor_id'];
        $toUpdate->start_date = Carbon::createFromFormat('d-m-Y',$keyValues['start_date'])->setTime(0,0,0);
        $toUpdate->end_date = Carbon::createFromFormat('d-m-Y',$keyValues['end_date'])->setTime(0,0,0);
        $toUpdate->description = $keyValues['description'];
        $toUpdate->save();
    } 
 
}
