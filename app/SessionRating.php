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
        // Add id==0 for the initial empty string. Will be validated against before submitting
        return SessionRating::where('selectable', 1)->orWhere('id', 0)->get();
    }
}
