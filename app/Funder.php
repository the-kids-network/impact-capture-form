<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Funder extends Model {
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

    public function fundings() {
        return $this->hasMany('App\Funding','funder_id');
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isManager() || Auth::user()->isAdmin()) {
           // no filter
        } else {
            $query->whereRaw('1 = 2');
        }
        return $query;
    }

}
