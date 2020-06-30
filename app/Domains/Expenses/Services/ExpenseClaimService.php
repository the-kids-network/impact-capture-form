<?php

namespace App\Domains\Expenses\Services;

use App\Domains\Expenses\Models\ExpenseClaim;
use App\Domains\Expenses\Models\ExpenseClaimSearch;

class ExpenseClaimService {

    public function getExpenseClaimsUsing(ExpenseClaimSearch $search) {
        $query = ExpenseClaim::canSee()->with(['expenses', 'receipts']);
        
        if ($search->sessionId) {
            $query->whereReportId($search->sessionId);
        }

        return $query->orderBy('created_at','desc')->get();
    }
}
