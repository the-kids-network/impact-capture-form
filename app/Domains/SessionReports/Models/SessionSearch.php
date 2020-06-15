<?php

namespace App\Domains\SessionReports\Models;

class SessionSearch {

    private $mentorId;
    private $menteeId;
    private $safeguardingId;
    private $sessionRatingId;
    private $sessionDateRangeStart;
    private $sessionDateRangeEnd;


    public function mentorId(?string $mentorId) {
        $this->mentorId = $mentorId;
        return $this;
    }

    public function menteeId(?string $menteeId) {
        $this->menteeId = $menteeId;
        return $this;
    }

    public function safeguardingId(?string $safeguardingId) {
        $this->safeguardingId = $safeguardingId;
        return $this;
    }
    public function sessionRatingId(?string $sessionRatingId) {
        $this->sessionRatingId = $sessionRatingId;
        return $this;
    }

    public function sessionDateRangeStart(?string $sessionDateRangeStart) {
        $this->sessionDateRangeStart = $sessionDateRangeStart;
        return $this;
    }

    public function sessionDateRangeEnd(?string $sessionDateRangeEnd) {
        $this->sessionDateRangeEnd = $sessionDateRangeEnd;
        return $this;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}