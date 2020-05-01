<?php

namespace App\Domains\Tagging\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaggedItem extends Model {

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function tags() {
        return $this->hasMany('App\Domains\Tagging\Models\Tag');
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
