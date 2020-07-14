<?php

namespace App\Domains\Expenses\Models;

class ExpenseClaimSearch {

    private $sessionId;

    private $mentorId;

    private $processedById;

    private $status;

    private $createdDateRangeStart;

    private $createdDateRangeEnd;

    public function sessionId(?string $sessionId) {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function mentorId(?string $mentorId) {
        $this->mentorId = $mentorId;
        return $this;
    }

    public function processedById(?string $processedById) {
        $this->processedById = $processedById;
        return $this;
    }

    public function status(?array $status) {
        $this->status = $status;
        return $this;
    }

    public function createdDateRangeStart(?string $createdDateRangeStart) {
        $this->createdDateRangeStart = $createdDateRangeStart;
        return $this;
    }

    public function createdDateRangeEnd(?string $createdDateRangeEnd) {
        $this->createdDateRangeEnd = $createdDateRangeEnd;
        return $this;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}