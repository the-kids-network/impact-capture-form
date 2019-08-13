<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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
        if (Auth::user()->isManager()) {
            $menteeIds = Auth::user()->assignedMentors
                            ->flatmap(function($m) { return $m->mentees; })
                            ->map(function($u) { return $u->id; });
            return $query->whereIn('mentee_id', $menteeIds);
        }
        else if (Auth::user()->isMentor()) {
            $menteeIds = Auth::user()->mentees
                            ->map(function($u) { return $u->id; });
            return $query->whereIn('mentee_id', $menteeIds);
        } else {
            $menteeIds = Mentee::all()
                            ->map(function($u) { return $u->id; });
            return $query->whereIn('mentee_id', $menteeIds);
        }
        return $query;
    }

}
