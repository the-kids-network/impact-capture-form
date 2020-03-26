<?php

namespace App\Domains\Documents\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Documents\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('uploadIndex', 'store', 'share', 'destroy', 'restore');
    }

    public function uploadIndex(Request $request) {
        return view('document.upload');
    }

    public function index(Request $request) {
        $documents = Document::canSee()->orderBy('updated_at', 'desc')->get();

        return view('document.index')->with('documents', $documents);
    }

    public function show(Request $request, $id) {
        $document = Document::canSee()->whereId($id)->first();
        if (!$document) abort(401,'Unauthorized');

        // get temporary url for download direct from storage
        $url = Storage::temporaryUrl(
            $document->path,
            now()->addMinutes(2),
            ['ResponseContentType' => 'application/octet-stream']
        );

        return redirect($url);
    }

    public function share($id, Request $request) {
        $document = Document::canModify()->whereId($id)->first();
        if (!$document) abort(401,'Unauthorized');

        $document->is_shared = filter_var($request->share, FILTER_VALIDATE_BOOLEAN);;
        $document->save();

        return redirect('/document')->with('status', 'Document '.(($document->is_shared) ? 'shared' : 'unshared').' to mentors.');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'file_attributes' => 'required',
            'file_attributes.*.shared' => 'required|in:true,false',
            'file_attributes.*.title' => 'required|string|max:200',
            'file' => 'required',
            'file.*' => 'mimes:jpeg,jpg,png,gif,bmp,pdf,doc,docx,dot,txt,text,mp4,avi,mkv,xls,xlsx,ppt,pptx,pps|max:200000'
        ]);

        $failedKeys = [];
        foreach (array_keys($request->file_attributes) as $key) {
            try {
                $attributes = $request->file_attributes[$key];
                $file = $request->file('file')[$key];

                // store/replace file in s3
                $path = Storage::putFileAs('documents', $file, $file->getClientOriginalName());

                // create/update document model
                $doc = Document::canModify()->wherePath($path)->first();
                if (!$doc) $doc = new Document();
                $doc->user_id = Auth::id();
                $doc->title = $attributes['title'];
                $doc->is_shared = filter_var($attributes['shared'], FILTER_VALIDATE_BOOLEAN);
                $doc->path = $path;
                $doc->save();

            } catch (\Exception $e) {
                Log::error("Failed to store file ".$key." with attributes ".json_encode($attributes));
                Log::error($e->getMessage());
                $failedKeys[$key] = ["Failed to store file ".$key];
            }
        }

        if ($failedKeys) {
            return response()->json(
                [
                    'code' => "D-1",
                    'message' => 'Failed to upload one or more files.',
                    'errors' => $failedKeys
                ], 
                500
            );
        } else {
            return response()->json(
                [
                    'status' => "Upload was successful."
                ]
            );
        }
    }

    public function destroy(Request $request) {
        $document = Document::canModify()->whereId($request->id)->first();
        if (!$document) abort(401,'Unauthorized');

        if ($request->really_delete) {
            $document->forceDelete();
            Storage::delete($document->path);
        } else {
            $document->delete();
        }

        return redirect('/document')
            ->with('status', ($request->really_delete) ? 'Document permanently deleted' : 'Document trashed');
    }

    public function restore($id) {
        $document = Document::canModify()->whereId($id)->first();
        if (!$document) abort(401,'Unauthorized');

        $document->restore();

        return redirect('/document')->with('status','Document restored');
    }
}
