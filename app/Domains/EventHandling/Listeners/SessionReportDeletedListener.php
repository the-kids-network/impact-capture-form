<?php

namespace App\Domains\EventHandling\Listeners;

use App\Domains\SessionReports\Events\SessionReportDeleted;
use App\ExpenseClaim;

class SessionReportDeletedListener {

    public function __construct() {
    }

    public function handle(SessionReportDeleted $event) {

        // This logic needs to go into a service in the expenses domain once it is created
        $expenseClaims = ExpenseClaim::whereReportId($event->reportId)->get();

        foreach ($expenseClaims as $claim) {
            $claim->expenses()->delete();
            $claim->receipts()->delete();
            $claim->delete();
        }
    }
}
