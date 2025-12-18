<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CryptoTestController;
use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

//testing
Route::get('/test/avalanche', [CryptoTestController::class, 'avalancheTest']);

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PelangganController::class, 'index'])->name('dashboard');

    Route::resource('pelanggan', PelangganController::class)->except(['show']);
    Route::get('/pelanggan/export/pdf', [PelangganController::class, 'exportPdf'])->name('pelanggan.export.pdf');
    Route::get('/pelanggan/export/excel', [PelangganController::class, 'exportExcel'])->name('pelanggan.export.excel');
});
