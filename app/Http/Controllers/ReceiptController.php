<?php

namespace App\Http\Controllers;

use App\Receipt;
use App\ExpenseClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('show', 'downloadAll');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        if (!Receipt::find($id)) abort(404);

        $receipt = Receipt::canSee()->find($id);

        if (!$receipt) abort(401, 'Unauthorized');

        return Storage::download($receipt->path);
    }

    public function downloadAll(Request $request) {
        $query = ExpenseClaim::canSee();

        if ($request->mentor_id) {
            $query->whereMentorId($request->mentor_id);
        }

        $receipts = $query->get()->flatmap(function($claim) {
            return $claim->receipts;
        });

        $zipFilePath = $this->createZipForReceipts($receipts);
        
        if (file_exists($zipFilePath)) {
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            abort(404);
        }
    }

    private function createZipForReceipts($receipts) {
        $zipFileName = uniqid($prefix = "receipts-");
        $zipFilePath = storage_path('app/'.$zipFileName.'.zip');
        $zip = new \ZipArchive;
        $zip->open($zipFilePath, \ZipArchive::CREATE);
        try {
            foreach ($receipts as $receipt) {
                $receiptFilePath = storage_path('app/'.$receipt->path);
                if (file_exists($receiptFilePath)) {
                    $zip->addFile($receiptFilePath, basename($receiptFilePath));
                }
            }
        }
        finally {
            $zip->close();
        }

        return $zipFilePath;
    }
}
