<?php

namespace App\Domains\Calendar\Models;

use App\Domains\UserManagement\Models\Mentee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class MenteeLeave extends Model
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

    public function mentee() {
        return $this->belongsTo('App\Domains\UserManagement\Models\Mentee');
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isManager()) {
            $ids = 
                collect(Auth::user()->assignedMentors)
                ->flatmap(function($m) { return $m->mentees; })
                ->map(function($m) { return $m->id; });
            $query->whereIn('mentee_id', $ids);
        }
        else if (Auth::user()->isMentor()) {
            $ids = 
                Auth::user()->mentees->map(function($m) { return $m->id; });
            $query->whereIn('mentee_id', $ids);
        } else {
            $ids = Mentee::get()->map(function($m) { return $m->id; });
            $query->whereIn('mentee_id', $ids);
        }
        return $query;
    }

}
