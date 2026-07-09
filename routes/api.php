<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProfilController;

// Public routes (tidak perlu login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::get('/kategori',  [KategoriController::class, 'index']);     
Route::get('/fakultas',  [AuthController::class, 'getFakultas']);
Route::get('/prodi',     [ProfilController::class, 'getProdiByFakultas']);

// Protected routes (harus login dulu)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::get('/me',       [AuthController::class, 'me']);

    // Profil
    Route::get('/profil',           [ProfilController::class, 'show']);
    Route::post('/profil/update',   [ProfilController::class, 'update']);
    Route::post('/profil/password', [ProfilController::class, 'updatePassword']);

    // Laporan Mahasiswa
    Route::get('/laporan',          [LaporanController::class, 'index']);
    Route::post('/laporan',         [LaporanController::class, 'store']);
    Route::get('/laporan/{id}',     [LaporanController::class, 'show']);
    Route::delete('/laporan/{id}',  [LaporanController::class, 'destroy']);
    Route::put('/laporan/{id}',     [LaporanController::class, 'update']);

    // Notifikasi
    Route::get('/notifikasi',               [NotifikasiController::class, 'index']);
    Route::post('/notifikasi/{id}/baca',    [NotifikasiController::class, 'markAsRead']);
    Route::post('/notifikasi/baca-semua',   [NotifikasiController::class, 'markAllAsRead']);

    // Admin only
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard',            [AdminController::class, 'dashboard']);
        Route::get('/laporan',              [AdminController::class, 'indexLaporan']);
        Route::get('/laporan/{id}',         [AdminController::class, 'showLaporan']);
        Route::post('/laporan/{id}/status', [AdminController::class, 'updateStatus']);
    });
});
