<?php

namespace App\Http\Controllers;

use App\Receipt;
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
        $receipt = Receipt::find($id);
        return Storage::download($receipt->path);
    }

    public function downloadAll(){
        $zip = new \ZipArchive;
        $download = storage_path('app/receipts.zip');
        $zip->open($download, \ZipArchive::CREATE);

        foreach (glob( storage_path('app/receipts/*') ) as $file) {
            $zip->addFile($file);
        }
        $zip->close();

        return response()->download($download)->deleteFileAfterSend(true);
    }
}
