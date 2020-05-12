<?php

namespace App\Domains\Tagging\Services;

use App\Domains\Tagging\Models\TaggedItem;
use App\Domains\Tagging\Models\TaggedItemSearch;
use App\Domains\Tagging\Repositories\TaggedItemRepository;

class TaggedItemService {

    private $taggedItemRepository;

    public function __construct(TaggedItemRepository $taggedItemRepository) {
        $this->taggedItemRepository = $taggedItemRepository;
    }

    public function createTaggedItem($resourceType, $resourceId) {
        $taggedItem = TaggedItem::whereResourceType($resourceType)
                                ->whereResourceId($resourceId)
                                ->first();
                
        if (!$taggedItem) {
            $taggedItem = new TaggedItem();
            $taggedItem->resource_id = $resourceId;
            $taggedItem->resource_type = $resourceType;
            $taggedItem->save();
        }

        return $taggedItem;
    }

    public function getTaggedItems(TaggedItemSearch $search) {
        return $this->taggedItemRepository->fetchTaggedItemsMatching($search);
    }
}
