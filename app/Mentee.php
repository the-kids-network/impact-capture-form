<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Mentee extends Model
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
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function mentor()
    {
        return $this->belongsTo('App\User','mentor_id');
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isManager()) {
            $mentorIds = Auth::user()->assignedMentors->map(function($m) { return $m->id; });
            $query->whereIn('mentor_id', $mentorIds);
        }
        else if (Auth::user()->isMentor()) {
            $query->where('mentor_id', Auth::user()->id);
        }
        return $query;
    }

}
