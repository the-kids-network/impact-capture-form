<?php

namespace App\Domains\Tagging\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Tagging\Models\TaggedItemSearch;
use App\Domains\Tagging\Services\TaggedItemService;
use Illuminate\Http\Request;

class TaggedItemController extends Controller {

    private $taggedItemService;

    public function __construct(TaggedItemService $taggedItemService) {
        $this->taggedItemService = $taggedItemService;
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager');
    }

    // get documents matching
    public function getTaggedItems(Request $request) {
        $this->validate($request, [
            'resource_type' => 'required|in:document',
            'resource_id' => 'integer'
        ]);
        
        $search = (new TaggedItemSearch())
            ->resourceId($request->get('resource_id'))
            ->resourceType($request->resource_type)
            ->tagLabels($request->get('tag_labels'));


        $taggedItems = $this->taggedItemService->getTaggedItems($search);

        $response = $taggedItems->map(function($taggedItem) {
            return [
                'id' => $taggedItem->id,
                'resource_type' => $taggedItem->resource_type,
                'resource_id' => $taggedItem->resource_id,
                'tags' => $taggedItem->tags->map(function($tag) {
                    return [
                        'id'=> $tag->id,
                        'label'=> $tag->label
                    ];
                })
            ];        
        });

        return response()->json($response);
    }

}
