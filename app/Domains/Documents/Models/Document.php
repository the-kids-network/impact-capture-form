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
        $path_parts = pathinfo($this->path);
        if (array_key_exists('extension', $path_parts)) {
            return $path_parts['extension'];
        }
        return null;
    }

    public function getTrashedAttribute() {
        return $this->trashed();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function scopeModifiable($query) {
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

    public function scopeViewable($query) {
        if (Auth::user()->isMentor()) {
            $query->whereIsShared(true);
        } else {
            $query->withTrashed();
        }

        return $query;
    }
}
