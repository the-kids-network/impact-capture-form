<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'path',
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
