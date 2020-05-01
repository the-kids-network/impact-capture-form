<?php

namespace App\Domains\Tagging\Repositories;

use App\Domains\Tagging\Models\TagSearch;
use Illuminate\Support\Facades\DB;

class TagRepository {

    public function fetchTagsMatching(TagSearch $tagSearch) {
        $query = DB::table('tags as t')
                    ->join('tagged_items as ti', 't.tagged_item_id', '=', 'ti.id')   
                    ->select('t.*', 'ti.id as tagged_item_id', 'ti.resource_type', 'ti.resource_id');

        // Build query
        if ($tagSearch->resourceType) {
            $query->where('ti.resource_type', '=', $tagSearch->resourceType);
        }
        if ($tagSearch->resourceId) {
            $query->where('ti.resource_id', '=', $tagSearch->resourceId);
        }
        if ($tagSearch->tagLabels) {
            $query->whereIn('t.label', $tagSearch->tagLabels);
        }

        // Get tags based on query
        return $query->get();
    }
}
