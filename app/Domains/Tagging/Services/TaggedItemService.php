<?php

namespace App\Domains\Tagging\Services;

use App\Domains\Tagging\Models\TaggedItem;

class TaggedItemService {

    public function __construct() {
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
}
