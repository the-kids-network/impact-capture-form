<?php

namespace App\Domains\SessionReports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Report extends Model
{

    protected $casts = [
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'session_date'
    ];

    public function mentee(){
        return $this->belongsTo('App\Mentee')->withTrashed();
    }

    public function mentor(){
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function activity_type(){
        return $this->belongsTo('App\Domains\SessionReports\Models\ActivityType')->withTrashed();
    }

    public function emotional_state(){
        return $this->belongsTo('App\Domains\SessionReports\Models\EmotionalState')->withTrashed();
    }

    public function session_rating() {
        return $this->belongsTo('App\Domains\SessionReports\Models\SessionRating', 'rating_id');
    }

    public function safeguardingConcernTypeAttribute() {
        if ($this->safeguarding_concern == 1)
            return "Serious";
        else if ($this->safeguarding_concern == 2)
            return "Mild";
        else
            return "None";
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isAdmin()) {
            // nothing to filter if admin
        } else if (Auth::user()->isManager()) {
            $ids = Auth::user()->assignedMentors->map(function($m) { return $m->id; });
            $query->whereIn('mentor_id', $ids);
        } else if (Auth::user()->isMentor()) {
            $query->whereMentorId(Auth::user()->id);
        } 

        return $query;
    }

}
