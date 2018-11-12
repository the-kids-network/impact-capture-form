<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
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
        'next_session_date'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function mentee(){
        return $this->belongsTo('App\Mentee')->first();
    }

}
