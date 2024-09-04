<?php

namespace App\Http\Controllers;

use App\Models\STNK;
use Illuminate\Http\Request;

class STNKController extends Controller
{
    //

    public function index(){
        $stnks = STNK::with('RelasiSTNKtoKendaraan')->get();
        return view('stnk.index',  ['stnks' => $stnks]);
    }

    public function create(){
        return view('stnk.add');
    }
}
