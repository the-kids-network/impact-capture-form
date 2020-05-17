<?php

namespace App\Domains\Tagging\Models;

class TaggedItemSearch {

    private $resourceId;
    private $resourceType;
    private $tabLabels;

    public function resourceId(?string $resourceId) {
        $this->resourceId = $resourceId;
        return $this;
    }

    public function resourceType(string $resourceType) {
        $this->resourceType = $resourceType;
        return $this;
    }

    public function tagLabels($tagLabels) {
        $this->tagLabels = $tagLabels;
        return $this;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}