<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;

#Login
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

#tambah user


Route::middleware('auth')->group(function () {
    
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    #Tambah user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    // Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    // Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    #Item
    Route::resource('items', ItemController::class);
    Route::resource('restock', RestockController::class);

    #Tampilan Kasir
    Route::get('/kasir', [TransaksiController::class, 'index'])->name('kasir');
    Route::post('/kasir/bayar', [TransaksiController::class, 'store'])->name('kasir.bayar');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    #Cetak PDF/Excel
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    #Grafik
    Route::get('/grafik-penjualan/{filter}', [DashboardController::class, 'grafikPenjualan']);
});

