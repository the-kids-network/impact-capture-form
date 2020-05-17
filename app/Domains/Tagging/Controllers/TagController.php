<?php

namespace App\Domains\Tagging\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Tagging\Models\TagSearch;
use App\Domains\Tagging\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller {

    private $tagService;

    public function __construct(TagService $tagService) {
        $this->tagService = $tagService;

        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('createTags', 'deleteTag');
    }

    // Right now, only supports finding by resource type and id
    // Though flexible enough design to extend
    public function getTags(Request $request) {
        $this->validate($request, [
            'resource_type' => 'required|in:document',
            'resource_id' => 'integer'
        ]);

        // Get tags based on query
        $search = (new TagSearch())
                ->resourceId($request->get('resource_id'))
                ->resourceType($request->resource_type)
                ->tagLabels($request->get('tag_labels'));

        $tags = $this->tagService->getTags($search);

        $response = 
            $tags->map(function($tag) {
                return [
                    'tagged_item' => [
                        'id' => $tag->tagged_item_id,
                        'resource_type' => $tag->resource_type,
                        'resource_id' => $tag->resource_id,
                    ],
                    'id' => $tag->id,
                    'label' => $tag->label
                ];
            });
   
        return response()->json($response);
    }

    public function createTags(Request $request) {
        $tagsToCreate = $request->json()->all();
        $validator = Validator::make($tagsToCreate, [
            '*.tagged_item.resource_type' => 'required|in:document',
            '*.tagged_item.resource_id' => 'required|integer',
            '*.tag_label' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->handleError($validator);
        }

        $createdTags = $this->tagService->createTags($tagsToCreate);

        $response = 
            collect($createdTags)->map(function($createdTag) {
                return [
                    'tagged_item' => [
                        'id' => $createdTag->taggedItem->id,
                        'resource_type' => $createdTag->taggedItem->resource_type,
                        'resource_id' => $createdTag->taggedItem->resource_id
                    ],
                    'id' => $createdTag->id,
                    'label' => $createdTag->label
                ];
            });

        return response()->json($response);
    }

    public function deleteTag(Request $request, $id) {
        $deletedTag = $this->tagService->deleteTag($id);

        return response()->json([
            'id' => $id,
        ]);
    }

}
