<?php

namespace App\Domains\Documents\Event;

use Illuminate\Queue\SerializesModels;

class DocumentDeleted {
    use SerializesModels;

    private $documentId;

    public function __construct($documentId) {
        $this->documentId = $documentId;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}
