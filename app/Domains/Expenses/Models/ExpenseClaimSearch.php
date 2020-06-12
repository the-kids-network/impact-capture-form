<?php

namespace App\Domains\Expenses\Models;

class ExpenseClaimSearch {

    private $sessionId;

    public function sessionId(?string $sessionId) {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}