<?php

namespace App\Domains\Documents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model {

    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = ['user_id'];

    protected $appends = ['extension', 'trashed'];

    public function getExtensionAttribute() {
        $infoPath = pathinfo($this->path);
        return $infoPath['extension'];
    }

    public function getTrashedAttribute() {
        return $this->trashed();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function scopeCanModify($query) {
        if (Auth::user()->isAdmin()) {
            $query->withTrashed();
        } else if (Auth::user()->isManager()){
            $query->withTrashed();
        } else {
            // force no results
            $query->where('1', '=', '2');
        }

        return $query;
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isMentor()) {
            $query->whereIsShared(true);
        } else {
            $query->withTrashed();
        }

        return $query;
    }
}
