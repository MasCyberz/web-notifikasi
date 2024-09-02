<?php

use App\Http\Controllers\KendaraanController;
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

// Authentication
Route::get('/', function () {
    return view('authentication.login');
})->name('login');

// Home / Dashboard
Route::get('/dashboard', function () {
    return view('home');
})->name('dashboard');

// Kendaraan
Route::get('/data-kendaraan', [KendaraanController::class, 'index'])->name('kendaraan-index');

Route::get('/data-kendaraan/tambah', [KendaraanController::class, 'create'])->name('kendaraan-tambah');

// STNK
Route::get('/data-stnk', function () {
    return view('stnk.index');
})->name('stnk-index');

Route::get('/data-stnk/tambah', function () {
    return view('stnk.add');
})->name('stnk-tambah');

// KIR
Route::get('/data-kir', function () {
    return view('kir.index');
})->name('kir-index');

Route::get('/data-kir/tambah', function () {
    return view('kir.add');
})->name('kir-tambah');

// ADMIN
// Management User
Route::get('/management-user', function () {
    return view('admin.management-user.index');
})->name('management-user-index');
