<?php

namespace App\Domains\Expenses\Controllers;

use App\Domains\Expenses\Models\Receipt;
use App\Domains\Expenses\Models\ExpenseClaim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager,mentor')->only('show');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('downloadAll');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id) {
        if (!Receipt::find($id)) abort(404);

        $receipt = Receipt::canSee()->find($id);

        if (!$receipt) abort(401, 'Unauthorized');

        return Storage::download($receipt->path);
    }

    public function get(Request $request) {
        // apply filters
        $query = ExpenseClaim::canSee();
        if ($request->mentor_id) {
            $query->whereMentorId($request->mentor_id);
        }

        // get receipts
        $receipts = $query->get()->flatmap(function($claim) {
            return $claim->receipts;
        });

        $zipFilePath = $this->createZip($receipts);
        
        if (file_exists($zipFilePath)) {
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            abort(404);
        }
    }

    private function copyReceiptFilesIntoTempDirectory($tempStore, $receipts) {
        $tempDirPath = "zip/receipts-".uniqid();
        foreach ($receipts as $receipt) {
            if (isset($receipt->path) && Storage::exists($receipt->path)) {
                $receiptFile = Storage::get($receipt->path);
                $tempReceiptFilePath = $tempDirPath."/".$receipt->path;
                $tempStore->put($tempReceiptFilePath, $receiptFile);
            }
        }
        return $tempDirPath;
    }

    private function createZip($receipts) {
        $localStorage = Storage::disk('local');

        // copy files into temp directory
        $tempDirPath = $this->copyReceiptFilesIntoTempDirectory($localStorage, $receipts);

        // Build zip from temp directory
        $dirFiles = glob(storage_path('app/'.$tempDirPath.'/**/*'));
        $zipFilePath = storage_path('app/'.$tempDirPath.'.zip');
        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE)) {
            try {
                foreach ($dirFiles as $file) {
                    $zip->addFile($file, basename($file));
                }
            }
            finally {
                $zip->close();
                $localStorage->deleteDirectory($tempDirPath);
            }
        }        

        return $zipFilePath;
    }
}
