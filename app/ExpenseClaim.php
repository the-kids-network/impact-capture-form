<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
        'approved_at',
        'processed_at'
    ];

    /**
     * Returns the Mentor associated with this Expense Claim
     * @return mixed
     */
    public function mentor(){
        return $this->belongsTo('App\User')->withTrashed();
    }

    /**
     * Returns the Report associated with this Expense Claim
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function report(){
        return $this->belongsTo('App\Report');
    }

    /**
     * Returns the Receipts associated with the Claim
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts(){
        return $this->hasMany('App\Receipt');
    }

    /**
     * Returns the Expenses associated with the Claim
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses(){
        return $this->hasMany('App\Expense');
    }

    /**
     * Returns the Manager who approved this claim
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy(){
        return $this->belongsTo('App\User','approved_by_id');
    }

    /**
     * Returns the Finance Person who processed this claim
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processedBy(){
        return $this->belongsTo('App\User','processed_by_id');
    }


}
