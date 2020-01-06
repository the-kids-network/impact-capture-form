<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Funding extends Model
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
    ];

    public function mentor() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function funder() {
        return $this->belongsTo('App\Funder', 'funder_id')->withTrashed();
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isManager()) {
            $ids = Auth::user()->assignedMentors->map(function($m) { return $m->id; });
            $query->whereIn('user_id', $ids);
        }
        else if (Auth::user()->isAdmin()) {
            $ids = User::mentor()->get()->map(function($m) { return $m->id; });
            $query->whereIn('user_id', $ids);
        } 
        else {
            // force no results
            $query->where('1', '=', '2');
        }
        return $query;
    }

}
