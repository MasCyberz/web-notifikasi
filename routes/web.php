<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\KIRController;
use App\Http\Controllers\STNKController;
use App\Http\Controllers\UserController;
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
Route::get('/', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticating'])->name('authenticating')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['middleware' => ['auth']], function () {
    // Home / Dashboard
    Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard')->middleware('auth');
    Route::get('/pemberitahuan-lainnya', [Controller::class, 'pemberitahuanlainnya'])->name('pemberitahuan-lainnya');
    Route::get('/detail-alert/{id}/{tipe}', [Controller::class, 'detailAlert'])->name('detail-alert');

// Kendaraan
    Route::get('/data-kendaraan', [KendaraanController::class, 'index'])->name('kendaraan-index');
// Detail Kendaraan
    Route::get('/data-kendaraan/detail/{id}', [KendaraanController::class, 'detail'])->name('kendaraan-detail');
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
    Route::get('/data-stnk', [STNKController::class, 'index'])->name('stnk-index');
    Route::get('/data-stnk/detail/{id}', [STNKController::class, 'detail'])->name('stnk-detail');
    Route::get('data-stnk/tambah', [STNKController::class, 'create'])->name('stnk-tambah');
    Route::post('data-stnk/store', [STNKController::class, 'store'])->name('stnk-store');
    Route::get('stnk/edit/{id}', [STNKController::class, 'editSTNK'])->name('stnk-edit');
    Route::put('stnk-update/{id}', [STNKController::class, 'updateSTNK'])->name('stnk-update');
    Route::get('/data-stnk/delete/{id}', [STNKController::class, 'deleteSTNK'])->name('stnk-delete');

// KIR
    Route::get('/data-kir', [KIRController::class, 'index'])->name('kir-index');
    Route::get('/data-kir/detail/{id}', [KIRController::class, 'detail'])->name('kir-detail');
// Create-KIR
    Route::get('/data-kir/tambah', [KIRController::class, 'create'])->name('kir-tambah');
    Route::post('/data-kir/store', [KIRController::class, 'store'])->name('kir-tambah-store');
// Edit KIR
    Route::get('/data-kir/edit/{id}', [KIRController::class, 'edit'])->name('kir-edit');
    Route::put('/data-kir/edit-store/{id}', [KIRController::class, 'update'])->name('kir-edit-store');
// Delete KIR
    Route::get('/data-kir/delete/{id}', [KIRController::class, 'delete'])->name('kir-delete-store');

    Route::group(['middleware' => ['auth', 'khususAdmin']], function () {
// ADMIN
// Management User
        Route::get('/management-user', [UserController::class, 'index'])->name('management-user-index');
        Route::get('management-user/tambah', [UserController::class, 'createUser'])->name('management-user-tambah');
        Route::post('management-user/store', [UserController::class, 'storeUser'])->name('management-user-store');
        Route::get('management-user/edit/{id}', [UserController::class, 'editUser'])->name('management-user-edit');
        Route::get('management-user/delete/{id}', [UserController::class, 'deleteUser'])->name('management-user-delete');
        Route::put('management-user/update/{id}', [UserController::class, 'updateUser'])->name('management-user-update');
    });

});
