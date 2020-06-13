<?php

namespace App\Domains\SessionReports\Models;

class SessionSearch {

    private $mentorId;
    private $menteeId;
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