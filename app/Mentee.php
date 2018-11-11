<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mentee extends Model
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
        'deleted_at'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function reports(){
        return $this->hasMany('App\Report');
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function mentor()
    {
        return $this->belongsTo('App\User','mentor_id');
    }

    public function schedules()
    {
        return $this->hasMany('App\Schedule')->get();
    }

    public static function allForUser($user)
    {
        if ($user->isAdmin())
        {
            return Mentee::all();
        }
        else
        {
            return Mentee::where('mentor_id', $user->id)->get();
        }
    }

}
