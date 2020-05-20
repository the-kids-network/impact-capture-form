<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Receipt extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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

    public function scopeCanSee($query) {
        if (Auth::user()->isAdmin()) {
            // nothing to filter if admin
        } else if (Auth::user()->isManager()) {
            $expenseClaimIds = 
                collect(Auth::user()->assignedMentors)
                ->flatmap(function($mentor) { return $mentor->expense_claims; })
                ->map(function($claim) { return $claim->id; });

            $query->whereIn('expense_claim_id', $expenseClaimIds);
        } else if (Auth::user()->isMentor()) {
            $expenseClaimIds =  
                collect(Auth::user()->expense_claims)
                ->map(function($claim) { return $claim->id; });
                        
            $query->whereIn('expense_claim_id', $expenseClaimIds);
        } 

        return $query;
    }
}
