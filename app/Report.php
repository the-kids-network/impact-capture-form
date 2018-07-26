<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $casts = [
      'safeguarding_concern' => 'boolean'
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
        return $this->belongsTo('App\ActivityType')->withTrashed();
    }

    public function emotional_state(){
        return $this->belongsTo('App\EmotionalState')->withTrashed();
    }

    public function physical_appearance(){
        return $this->belongsTo('App\PhysicalAppearance')->withTrashed();
    }

    public function expense_claims(){
        return $this->hasMany('App\ExpenseClaim');
    }

}
