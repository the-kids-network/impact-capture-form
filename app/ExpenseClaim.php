<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ExpenseClaim extends Model {
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'approved_at',
        'processed_at'
    ];

    /**
     * Returns the Mentor associated with this Expense Claim
     * @return mixed
     */
    public function mentor() {
        return $this->belongsTo('App\Domains\UserManagement\Models\User')->withTrashed();
    }

    /**
     * Returns the Receipts associated with the Claim
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts() {
        return $this->hasMany('App\Receipt');
    }

    /**
     * Returns the Expenses associated with the Claim
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses() {
        return $this->hasMany('App\Expense');
    }

    /**
     * Returns the Finance Person who processed this claim
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processedBy() {
        return $this->belongsTo('App\Domains\UserManagement\Models\User','processed_by_id');
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isAdmin()) {
            // nothing to filter if admin
        } else if (Auth::user()->isManager()) {
            $ids = Auth::user()->assignedMentors->map(function($m) { return $m->id; });
            $query->whereIn('mentor_id', $ids);
        } else if (Auth::user()->isMentor()) {
            $query->whereMentorId(Auth::user()->id);
        } 

        return $query;
    }

}
