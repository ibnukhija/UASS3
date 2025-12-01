<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

#Login
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('items', ItemController::class);
    Route::resource('restock', RestockController::class);

    #Tampilan Kasir
    Route::get('/kasir', [TransaksiController::class, 'index'])->name('kasir');
    Route::post('/kasir/bayar', [TransaksiController::class, 'store'])->name('kasir.bayar');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    #Cetak PDF/Excel
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});