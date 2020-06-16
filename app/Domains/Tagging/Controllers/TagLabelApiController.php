<?php

namespace App\Domains\Tagging\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Tagging\Services\TagService;
use Illuminate\Http\Request;

class TagLabelApiController extends Controller {

    private $tagService;

    public function __construct(TagService $tagService) {
        $this->tagService = $tagService;

        $this->middleware('auth');
    }

    public function getAssociated(Request $request) {
        $this->validate($request, [
            'tag_labels' => 'required',
        ]);

        // find associated tag labels 
        $associations = $this->tagService->getAssociatedTagLabels($request->tag_labels);
   
        return response()->json($associations);
    }

}
