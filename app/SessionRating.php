<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessionRating extends Model
{
    public $timestamps = false;

    public function reports(){
        return $this->hasMany('App\Report', 'rating_id');
    }

    public static function selectable() {
        return SessionRating::where('selectable', 1)->get();
    }
}
