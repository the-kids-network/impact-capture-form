<?php

namespace App\Domains\Tagging\Repositories;

use App\Domains\Tagging\Models\TaggedItem;
use App\Domains\Tagging\Models\TaggedItemSearch;
use Illuminate\Support\Facades\DB;

class TaggedItemRepository {

    public function fetchTaggedItemsMatching(TaggedItemSearch $search) {
        // https://stackoverflow.com/questions/4763143/sql-for-applying-conditions-to-multiple-rows-in-a-join

        $query = $this->buildQuery($search);

        $taggedItemIds = $query->get()->map(function($item) {
            return $item->id;
        });

        return TaggedItem::whereIn('id', $taggedItemIds)->get();
    }

    private function buildQuery(TaggedItemSearch $search) {
        $addTagLabelRestrictions = function($query, $tagLabel, $key) {
            $query->join("tags as t_{$key}", function ($join) use($tagLabel, $key) {
                $join->on("t_{$key}.tagged_item_id", '=', "ti.id")
                     ->where("t_{$key}.label", '=', $tagLabel);
            });
        };

        $query = DB::table('tagged_items as ti');
        
        // purpose here is to find tagged items that have ALL of the supplied search tag labels
        collect($search->tagLabels)
            ->each(function($tagLabel, $key) use($query, $addTagLabelRestrictions) {
                $addTagLabelRestrictions($query, $tagLabel, $key);
            });

        $query->select('ti.id');

        if ($search->resourceType) {
            $query->where('resource_type', $search->resourceType);
        }

        if ($search->resourceId) {
            $query->where('resource_id', $search->resourceId);
        }

        return $query;
    }

}
