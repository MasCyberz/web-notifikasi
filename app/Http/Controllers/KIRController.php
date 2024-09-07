<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KIRController extends Controller
{
    public function index()
    {
        return view('kir.index');
    }

    public function create(){
        return view('kir.add');
    }
}
