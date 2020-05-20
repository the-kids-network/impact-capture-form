<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
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
