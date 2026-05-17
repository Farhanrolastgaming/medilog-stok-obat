<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ReportController;

// Route untuk fitur Login & Logout
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth.login')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pemasok', PemasokController::class);
    Route::resource('obat', ObatController::class);
    Route::resource('user', UserController::class);
    Route::resource('transaksi', TransaksiController::class);

    Route::get('/report/stok', [ReportController::class, 'laporanStok'])->name('report.stok');
    Route::get('/report/barang-masuk', [ReportController::class, 'laporanBarangMasuk'])->name('report.barang-masuk');
    Route::get('/report/barang-keluar', [ReportController::class, 'laporanBarangKeluar'])->name('report.barang-keluar');
});
