<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class MentorLeave extends Model
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
        'start_date',
        'end_date'
    ];

    public function mentor() {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isManager()) {
            $ids = Auth::user()->assignedMentors->map(function($m) { return $m->id; });
            $query->whereIn('mentor_id', $ids);
        }
        else if (Auth::user()->isMentor()) {
            $query->whereMentorId(Auth::user()->id);
        }
        return $query;
    }

}
