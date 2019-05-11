<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Spark\User as SparkUser;
use Laravel\Spark\Spark;

class User extends SparkUser
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'reminder_emails'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
        'reminder_emails' => 'datetime'
    ];

    /**
     * Reports that this mentor has written
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports(){
        return $this->hasMany('App\Report','mentor_id');
    }

    /**
     * Expense Claims that this mentor has submitted
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expense_claims(){
        return $this->hasMany('App\ExpenseClaim','mentor_id');
    }

    public function hasRole($role) {
        return ($this->role && $this->role == $role);
    }

    public function isManager()
    {
        return ($this->role && $this->role == 'manager');
    }
    public function isFinance()
    {
        return ($this->role && $this->role == 'finance');
    }
    public function isMentor()
    {
        return (!$this->role);
    }
    public function isMentorOnly()
    {
        return ($this->isMentor() && !$this->isDeveloper());
    }
    public function isDeveloper(){
        return Spark::developer($this->email) || $this->role == 'admin';
    }
    public function isAdmin(){
        return $this->isDeveloper();
    }

    public function manager(){
        return $this->belongsTo('App\User','manager_id');
    }
    public function assignedMentors(){
        return $this->hasMany('App\User','manager_id');
    }
    public function mentees()
    {
        return $this->hasMany('App\Mentee','mentor_id');
    }


    public function approvedClaims(){
        return $this->hasMany('App\ExpenseClaim','approved_by_id');
    }
    public function processedClaims(){
        return $this->hasMany('App\ExpenseClaim','processed_by_id');
    }

}
