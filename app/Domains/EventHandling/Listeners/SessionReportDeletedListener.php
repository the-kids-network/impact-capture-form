<?php

namespace App\Domains\EventHandling\Listeners;

use App\Domains\SessionReports\Events\SessionReportDeleted;
use App\ExpenseClaim;

class SessionReportDeletedListener {

    public function __construct() {
    }

    public function handle(SessionReportDeleted $event) {

        // This logic needs to go into a service in the expenses domain once it is created
        $expenseClaim = ExpenseClaim::whereReportId($event->reportId)->first();

        $expenseClaim->expenses()->delete();
        $expenseClaim->receipts()->delete();
        $expenseClaim->delete();
    }
}
