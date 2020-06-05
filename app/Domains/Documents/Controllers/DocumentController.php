<?php

namespace App\Domains\Documents\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Documents\Services\DocumentService;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;

class DocumentController extends Controller {

    private $documentService;

    public function __construct(DocumentService $documentService) {
        $this->documentService = $documentService;

        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('uploadIndex', 'store', 'share', 'delete', 'restore');
    }

    public function uploadIndex() {
        return view('documents.upload');
    }

    public function index() {
        return view('documents.index');
    }

    public function getAll() {
        $documents = $this->documentService->getAll();

        return response()->json($documents);
    }

    public function getOne(Request $reuqest, $id) {
        abort(405, 'Method not allowed');
    }

    public function download($id) {
        $url = null;
        try {
            $url = $this->documentService->getViewableUrlFor($id);
        } catch (NotFoundException $e) {
            abort(401,'Unauthorized');
        }

        return response()->json([
            'id' => $id,
            'download_url' => $url
        ]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'file_attributes' => 'required',
            'file_attributes.*.shared' => 'required|in:true,false',
            'file_attributes.*.title' => 'required|string|max:200',
            'file' => 'required',
            'file.*' => 'mimes:jpeg,jpg,png,gif,bmp,pdf,doc,docx,dot,txt,text,mp4,avi,mkv,xls,xlsx,ppt,pptx,pps|max:200000'
        ]);

        $failedKeys = $this->documentService->store($request->file_attributes, $request->file('file'));

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

    public function share(Request $request, $id) {
        $share = filter_var($request->json()->all()['share'], FILTER_VALIDATE_BOOLEAN);

        $document = null;
        try {
            $document = $this->documentService->share($id, $share);
        } catch (NotFoundException $e) {
            abort(401,'Unauthorized');
        }

        return response()->json($document);
    }

    public function delete(Request $request) {
        $reallyDelete = filter_var($request->really_delete, FILTER_VALIDATE_BOOLEAN);

        $document = null;
        try {
            $document = $this->documentService->delete($request->id, $reallyDelete);
        } catch (NotFoundException $e) {
            abort(401,'Unauthorized');
        }

        return response()->json($document);
    }

    public function restore($id) {
        $document = null;
        try {
            $document = $this->documentService->restore($id);
        } catch (NotFoundException $e) {
            abort(401,'Unauthorized');
        }

        return response()->json($document);
    }
}
