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

    public function fetchTagLabelsAssociatedWith($tagLabels) {
        $data =  DB::table('tags as t')
                    ->join('tags as t2', 't.tagged_item_id', '=', 't2.tagged_item_id')
                    ->select('t.label', 't2.label as associated_label')->distinct()
                    ->whereColumn('t.label', '!=', 't2.label')
                    ->whereIn('t.label', $tagLabels)
                    ->orderBy('t.label', 'asc')
                    ->orderBy('t2.label', 'asc')
                    ->get();

        return $data->groupBy('label')->map(function($group) {
            return $group->map(function($item) {
                return $item->associated_label;
            });
        });
    }
}
