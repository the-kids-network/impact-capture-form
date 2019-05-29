<?php

namespace App\Http\Controllers;

use App\ExpenseClaim;
use App\Report;
use App\User;
use Illuminate\Http\Request;

class ManagerController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('manager');
    }

    public function index() {
        return view('manager.index');
    }
}
