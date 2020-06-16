<?php

namespace App\Domains\Documents\Services;

use App\Domains\Documents\Events\DocumentDeleted;
use App\Domains\Documents\Models\Document;
use App\Domains\Documents\Models\DocumentSearch;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentService {

    public function getWithSearch(DocumentSearch $search) {
        $query = Document::viewable();
        
        if ($search->documentIds) {
            $query->whereIn('id', $search->documentIds);
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    public function getViewableUrlFor($documentId) {
        $document = Document::viewable()->whereId($documentId)->first();
        if (!$document) throw new NotFoundException("Document not found");

        // get temporary url for download direct from storage
        $url = Storage::temporaryUrl(
            $document->path,
            now()->addMinutes(2),
            ['ResponseContentType' => 'application/octet-stream']
        );

        return $url;
    }

    public function share($documentId, $share=true) {
        $document = Document::modifiable()->whereId($documentId)->first();
        if (!$document) throw new NotFoundException("Document not found");

        $document->is_shared = $share;
        $document->save();

        return $document;
    }

    public function store($fileAttributes, $files) {
        $failedKeys = [];
        foreach (array_keys($fileAttributes) as $key) {
            try {
                $attributes = $fileAttributes[$key];
                $file = $files[$key];

                // store/replace file in s3
                $path = Storage::putFileAs('documents', $file, $file->getClientOriginalName());

                // create/update document model
                $doc = Document::modifiable()->wherePath($path)->first();
                if (!$doc) $doc = new Document();
                $doc->user_id = Auth::id();
                $doc->title = strip_tags($attributes['title']);
                $doc->is_shared = filter_var($attributes['shared'], FILTER_VALIDATE_BOOLEAN);
                $doc->path = $path;
                $doc->save();

            } catch (\Exception $e) {
                Log::error("Failed to store file ".$key." with attributes ".json_encode($attributes));
                Log::error($e->getMessage());
                $failedKeys[$key] = ["Failed to store file ".$key];
            }
        }
        return $failedKeys;
    }

    public function delete($documentId, $reallyDelete=false) {
        $document = Document::modifiable()->whereId($documentId)->first();
        if (!$document) throw new NotFoundException("Document not found");

        if ($reallyDelete) {
            $document->forceDelete();
            Storage::delete($document->path);
            event(new DocumentDeleted($documentId));
        } else {
            $document->delete();
        }

        return $document;
    }

    public function restore($documentId) {
        $document = Document::modifiable()->whereId($documentId)->first();
        if (!$document) throw new NotFoundException("Document not found");

        $document->restore();

        return $document;
    }
  
}