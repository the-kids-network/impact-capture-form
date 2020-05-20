<?php

namespace App\Domains\Calendar\Models;

use App\Mentee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PlannedSession extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date',
        'last_email_reminder'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function mentee(){
        return $this->belongsTo('App\Mentee');
    }

    public function scopeCanSee($query) {
        if (Auth::user()) {
            if (Auth::user()->isManager()) {
                $menteeIds = Auth::user()->assignedMentors
                                ->flatmap(function($m) { return $m->mentees; })
                                ->map(function($u) { return $u->id; });
                $query->whereIn('mentee_id', $menteeIds);
            }
            else if (Auth::user()->isMentor()) {
                $menteeIds = Auth::user()->mentees->map(function($u) { return $u->id; });
                $query->whereIn('mentee_id', $menteeIds);
            } 
            else if (Auth::user()->isAdmin()) {
                $menteeIds = Mentee::all()->map(function($u) { return $u->id; });
                $query->whereIn('mentee_id', $menteeIds);
            }
        }
        return $query;
    }

    public function isReminderEmailNeeded() {
        $emailSentAlreadyToday = function () {
            if (!isset($this->last_email_reminder)) return false;
            $diff = $this->last_email_reminder->diff(Carbon::now());
            $emailSentToday = $diff->days == 0;
            Log::debug("Has reminder email has already been sent today? --> ".var_export($emailSentToday, true));
            return $emailSentToday;
        };

        $atSpecificDaySincePlannedSessionDate = function() {
            $plannedSessionDate = $this->date->setTime(20,00);
            $diff = $plannedSessionDate->diff(Carbon::now());
            Log::debug("Time since planned session date (in days) --> ".$diff->days);
            return $diff->days == 2 || $diff->days == 3 || $diff->days == 5;
        };

        $emailNeeeded = !$emailSentAlreadyToday() && $atSpecificDaySincePlannedSessionDate();

        Log::debug("Reminder email required? --> ".var_export($emailNeeeded, true));
        return $emailNeeeded;
    }

    public function hasBeenAtLeastTwoDaysSincePlannedSessionDate() {
        $plannedSessionDate = $this->date->setTime(20,00);
        $diff = $plannedSessionDate->diff(Carbon::now());
        $result = $diff->days > 2;

        Log::debug("Has been more than two days since planned session date? --> ".var_export($result, true));
        return $result;
    }

}
