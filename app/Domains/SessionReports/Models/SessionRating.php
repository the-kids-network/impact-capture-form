<?php

namespace App\Domains\SessionReports\Models;

use Illuminate\Database\Eloquent\Model;

class SessionRating extends Model
{
    public $timestamps = false;

    public static function selectable() {
        // Add id==0 for the initial empty string. Will be validated against before submitting
        return SessionRating::where('selectable', 1)->orWhere('id', 0)->get();
    }
}
