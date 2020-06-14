<?php

namespace App\Domains\Documents\Controllers;

use App\Http\Controllers\Controller;

class DocumentController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager')->only('uploadIndex');
    }

    public function uploadIndex() {
        return view('documents.upload');
    }

    public function index() {
        return view('documents.index');
    }
}
