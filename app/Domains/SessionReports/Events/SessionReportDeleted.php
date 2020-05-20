<?php

namespace App\Domains\SessionReports\Events;

use Illuminate\Queue\SerializesModels;

class SessionReportDeleted {
    use SerializesModels;

    private $reportId;

    public function __construct($reportId) {
        $this->reportId = $reportId;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}
