<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        return view('kendaraan.index');
    }

    public function create(){
        return view('kendaraan.add');
    }
}
