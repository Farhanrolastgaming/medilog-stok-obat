<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\ObatRusakController;
use App\Http\Controllers\PemesananStokController;

// Route untuk fitur Login & Logout
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth.login')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Pemesanan (Dapat diakses User dan Admin)
    Route::resource('pemesanan', PemesananStokController::class)->only(['index', 'create', 'store']);
    Route::get('/pemesanan/{id}/cetak', [App\Http\Controllers\PemesananStokController::class, 'cetak'])->name('pemesanan.cetak');

    // Transaction routes (Accessible to both User & Admin)
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('obat-rusak', ObatRusakController::class);
    
    // Cetak Transaksi & Retur
    Route::get('/barang-masuk/{id}/cetak', [App\Http\Controllers\BarangMasukController::class, 'cetak'])->name('barang-masuk.cetak');
    Route::get('/barang-keluar/{id}/cetak', [App\Http\Controllers\BarangKeluarController::class, 'cetak'])->name('barang-keluar.cetak');
    Route::get('/obat-rusak/{id}/cetak', [ObatRusakController::class, 'cetak'])->name('obat-rusak.cetak');

    // Report routes (Accessible to both User & Admin)
    Route::get('/report/stok', [ReportController::class, 'laporanStok'])->name('report.stok');
    Route::get('/report/barang-masuk', [ReportController::class, 'laporanBarangMasuk'])->name('report.barang-masuk');
    Route::get('/report/barang-keluar', [ReportController::class, 'laporanBarangKeluar'])->name('report.barang-keluar');
    Route::delete('/report/detail-transaksi/{id}/force', [ReportController::class, 'hapusPermanenDetail'])->name('report.detail.forceDelete');

    // Route yang hanya bisa diakses oleh Admin
    Route::middleware('admin')->group(function () {
        Route::patch('/pemesanan/{id}/status', [App\Http\Controllers\PemesananStokController::class, 'updateStatus'])->name('pemesanan.updateStatus');
        Route::resource('user', UserController::class);
        Route::resource('transaksi', TransaksiController::class);

        // Write routes for Obat & Pemasok (Only Admin can create, edit, delete)
        Route::resource('obat', ObatController::class)->except(['index', 'show']);
        Route::delete('/stok-batch/{id}', [ObatController::class, 'destroyBatch'])->name('stok-batch.destroy');
        Route::resource('pemasok', PemasokController::class)->except(['index', 'show']);
    });

    // Read-only routes for Obat & Pemasok (Accessible to all authenticated users)
    Route::resource('obat', ObatController::class)->only(['index', 'show']);
    Route::resource('pemasok', PemasokController::class)->only(['index', 'show']);
});
