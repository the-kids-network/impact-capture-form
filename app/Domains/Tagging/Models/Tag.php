<?php

namespace App\Domains\Tagging\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tag extends Model {

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function taggedItem(){
        return $this->belongsTo('App\Domains\Tagging\Models\TaggedItem');
    }

    public function scopeUser($query) {
        if (Auth::user()->isAdmin() || Auth::user()->isManager()) {
            $query->withTrashed();
        } else {
            // force no results
            $query->where('1', '=', '2');
        }

        return $query;
    }

}
