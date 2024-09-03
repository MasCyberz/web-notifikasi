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
// Tambah Kendaraan
Route::get('/data-kendaraan/tambah', [KendaraanController::class, 'create'])->name('kendaraan-tambah');
Route::post('/data-kendaraan/tambah-store', [KendaraanController::class, 'store'])->name('kendaraan-store-add');
// Edit Kendaraan
Route::get('/data-kendaraan/edit/{id}', [KendaraanController::class, 'edit'])->name('kendaraan-edit');
Route::put('/data-kendaraan/edit-store/{id}', [KendaraanController::class, 'update'])->name('kendaraan-store-edit');
// Delete
Route::get('/data-kendaraan/delete/{id}', [KendaraanController::class, 'delete'])->name('kendaraan-store-delete');
// Tambah Models Baru
Route::post('/data-kendaraan/tambah-models', [KendaraanController::class, 'storeNewModels'])->name('models-kendaraan-store-add');
Route::post('/data-kendaraan/hapus-models', [KendaraanController::class, 'deleteModels'])->name('models-kendaraan-store-delete');

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

Route::get('/management-user/tambah', function () {
    return view('admin.management-user.add');
})->name('management-user-tambah');

Route::get('/management-user/edit', function () {
    return view('admin.management-user.edit');
})->name('management-user-edit');
