<?php

namespace App\Domains\Documents\Models;

class DocumentSearch {

    private $documentIds;

    public function documentIds($documentIds) {
        $this->documentIds = $documentIds;
        return $this;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}