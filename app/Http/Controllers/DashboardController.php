<?php

namespace App\Http\Controllers;

use App\Models\STNK;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $dataUser = User::count();
        return view('home', ['dataUser' => $dataUser]);
    }
}
