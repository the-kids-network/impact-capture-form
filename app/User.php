<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Spark\User as SparkUser;
use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Auth;

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
        if ($role == "mentor") {
            return !$this->role;
        } else {
            return $this->role && $this->role == $role;
        }
    }

    public function isManager() {
        return $this->role && $this->role == 'manager';
    }

    public function isMentor() {
        return !$this->role;
    }

    public function isAdmin() {
        return $this->role == 'admin';
    }

    public function manager() {
        return $this->belongsTo('App\User','manager_id');
    }

    public function assignedMentors() {
        return $this->hasMany('App\User','manager_id');
    }

    public function mentees() {
        return $this->hasMany('App\Mentee','mentor_id');
    }

    public function processedClaims() {
        return $this->hasMany('App\ExpenseClaim', 'processed_by_id')->where('status', 'processed');
    }

    public function rejectedClaims() {
        return $this->hasMany('App\ExpenseClaim','processed_by_id')->where('status', 'rejected');
    }

    public function processedAndRejectedClaims() {
        return $this->hasMany('App\ExpenseClaim','processed_by_id')->whereIn('status', ['rejected', 'processed']);
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isAdmin()) {
            // show all so no restriction
        }
        else if (Auth::user()->isManager()) {
            $query->whereManagerId(Auth::user()->id);
        }
        else if (Auth::user()->isMentor()) {
            $query->find(Auth::user()->id);
        }
        return $query;
    }

    public function scopeIsMentor($query) {
        $query->whereNull('role');
        return $query;
    }
}
