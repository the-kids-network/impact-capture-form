<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
        'date'
    ];

    protected $fillable = [
        'date',
        'amount',
        'description',
        'expense_claim_id'
    ];

    /**
     * Returns the Claim that is associated with this receipt
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expense_claim(){
        return $this->belongsTo('App\ExpenseClaim');
    }
}
