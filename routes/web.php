<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root ke halaman login atau dashboard sesuai status autentikasi
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->peran) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('kasir.dashboard'),
            'pemilik' => redirect()->route('pemilik.dashboard'),
            default => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
});

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rute Role: ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Management Pengguna (CRUD)
    Route::resource('users', UserController::class);
});

// Modul Master Bahan Baku (Khusus Admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/bahan-baku', [BahanBakuController::class, 'index'])->name('bahan-baku.index');
    Route::get('/bahan-baku/create', [BahanBakuController::class, 'create'])->name('bahan-baku.create');
    Route::post('/bahan-baku', [BahanBakuController::class, 'store'])->name('bahan-baku.store');

    Route::get('/menu/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/menu', [MenuController::class, 'store'])->name('menus.store');
});

// Rute Role: KASIR
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kasir'])->name('dashboard');
});

// Rute Role: PEMILIK
Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pemilik'])->name('dashboard');
});

// Rute Berbagi Berdasarkan Matriks Hak Akses

// 1. Melihat Menu (Admin, Kasir, Pemilik)
Route::middleware(['auth', 'role:admin,kasir,pemilik'])->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menus.index');
});

// 2. Transaksi Penjualan (Admin & Kasir)
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
});

// 3. Laporan Penjualan (Admin & Pemilik)
Route::middleware(['auth', 'role:admin,pemilik'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
