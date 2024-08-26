<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('authentication.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('authentication.login');
});

Route::get('/dashboard', function () {
    return view('home');
})->name('dashboard');

Route::get('/data-stnk', function () {
    return view('stnk.index');
})->name('stnk-index');

Route::get('/data-stnk/tambah', function () {
    return view('stnk.add');
})->name('stnk-tambah');
