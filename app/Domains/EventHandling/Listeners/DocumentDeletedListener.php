<?php

namespace App\Domains\EventHandling\Listeners;

use App\Domains\Documents\Event\DocumentDeleted;
use App\Domains\Tagging\Services\TaggedItemService;

class DocumentDeletedListener {
    private $taggedItemService;

    public function __construct(TaggedItemService $taggedItemService) {
        $this->taggedItemService = $taggedItemService;
    }

    public function handle(DocumentDeleted $event) {
        $this->taggedItemService->deleteTaggedItem("document", $event->documentId);
    }
}
