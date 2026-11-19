<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockController;

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Halaman Utama ────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ─── Rute Terproteksi (harus login) ──────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin only ─────────────────────────────────────────────────────────
    Route::middleware(['auth'])->group(function () {

        // Kelola Pengguna (Admin)
        Route::resource('users', UserController::class)->except(['show']);

        // Kelola Kategori (Admin)
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Kelola Produk (Admin)
        Route::resource('products', ProductController::class);

        // Kelola Stok
        Route::get('/stock',        [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/tambah', [StockController::class, 'create'])->name('stock.create');
        Route::post('/stock',       [StockController::class, 'store'])->name('stock.store');
        Route::get('/stock/logs',   [StockController::class, 'logs'])->name('stock.logs');
    });

    // ── Kasir ──────────────────────────────────────────────────────────────
    // Kelola Pelanggan
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Transaksi Penjualan
    Route::resource('transactions', TransactionController::class)->only([
        'index', 'create', 'store', 'show', 'destroy',
    ]);
});
