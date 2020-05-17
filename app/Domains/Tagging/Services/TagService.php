<?php

namespace App\Domains\Tagging\Services;

use App\Domains\Tagging\Models\Tag;
use App\Domains\Tagging\Models\TagSearch;
use App\Domains\Tagging\Repositories\TagRepository;
use Illuminate\Support\Facades\DB;

class TagService {

    private $tagRepository;
    private $taggedItemService;

    public function __construct(TaggedItemService $taggedItemService, TagRepository $tagRepository) {
        $this->tagRepository = $tagRepository;
        $this->taggedItemService = $taggedItemService;
    }

    public function getTags(TagSearch $tagSearch) {
        return $this->tagRepository->fetchTagsMatching($tagSearch);
    }

    public function createTag($taggedItem, $tagLabel) {
        $tag = Tag::whereTaggedItemId($taggedItem->id)
                            ->whereLabel(strtolower(trim($tagLabel)))
                            ->first();

        if (!$tag) {
            $tag = new Tag();
            $tag->tagged_item_id = $taggedItem->id;
            $tag->label = strip_tags(strtolower(trim($tagLabel)));
            $tag->save();
        } 

        return $tag;
    }

    public function createTags($tagsToCreate) {
        return DB::transaction(function() use ($tagsToCreate) {
            
            $tags = [];
            foreach ($tagsToCreate as $tagToCreate) { 
                $taggedItem = $this->taggedItemService->createTaggedItem($tagToCreate['tagged_item']['resource_type'], $tagToCreate['tagged_item']['resource_id']);
                $tag = $this->createTag($taggedItem, $tagToCreate['tag_label']);
                $tags[] = $tag;
            }

            return $tags;
        });
    }

    public function deleteTag($id) {
        return Tag::whereId($id)->delete();
    }

    public function getAssociatedTagLabels($tagLabels) {
        return $this->tagRepository->fetchTagLabelsAssociatedWith($tagLabels);
    }
}
