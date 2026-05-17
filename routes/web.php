<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\AuthController;

// Route untuk fitur Login & Logout
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/', function () {
    return view('welcome');
});

Route::resource('pemasok', PemasokController::class);
Route::get('/dashboard', function () {
    return view('dasboard');
})->middleware('auth.login');
