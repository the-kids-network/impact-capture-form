<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interactions\Support\SendSupportEmail;

class VueController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('vue.index');
    }
}
