<?php

namespace App\Domains\Calendar\Services;

use App\Domains\Calendar\Models\MenteeLeave;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Domains\UserManagement\Models\Mentee;
use Carbon\Carbon;

class MenteeLeaveService {
   
    public function getMenteeLeaves() {
        return MenteeLeave::canSee()->get();
    }

    public function getMenteeLeave($id) {
        $menteeLeave =  MenteeLeave::canSee()->find($id);
        if (!$menteeLeave) throw new NotFoundException("Mentee leave not found");
        return $menteeLeave;
    }
    
    public function deleteMenteeLeave($id) {
        $toDelete = MenteeLeave::canSee()->find($id);
        if (!$toDelete) throw new NotFoundException("Mentee leave not found");
        MenteeLeave::destroy($toDelete->id);
    }

    public function createMenteeLeave($keyValues) {
        if (!Mentee::canSee()->find($keyValues['mentee_id'])) {
            throw new NotAuthorisedException("Current user cannot manage mentee's leave"); 
        }

        $leave = new MenteeLeave();
        $leave->mentee_id = $keyValues['mentee_id'];
        $leave->start_date = Carbon::createFromFormat('d-m-Y', $keyValues['start_date'])->setTime(0,0,0);
        $leave->end_date = Carbon::createFromFormat('d-m-Y', $keyValues['end_date'])->setTime(0,0,0);
        $leave->description = $keyValues['description'];
        $leave->save();

        return $leave;
    } 

    public function updateMenteeLeave($id, $keyValues) {
        if (!Mentee::canSee()->find($keyValues['mentee_id'])) {
            throw new NotAuthorisedException("Current user cannot manage mentee's leave"); 
        }

        $toUpdate = MenteeLeave::canSee()->find($id);
        if (!$toUpdate) throw new NotFoundException("Mentee leave not found");

        $toUpdate->mentee_id = $keyValues['mentee_id'];
        $toUpdate->start_date = Carbon::createFromFormat('d-m-Y', $keyValues['start_date'])->setTime(0,0,0);
        $toUpdate->end_date = Carbon::createFromFormat('d-m-Y', $keyValues['end_date'])->setTime(0,0,0);
        $toUpdate->description = $keyValues['description'];
        $toUpdate->save();

        return $toUpdate;
    } 

}
