<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Dashboard Utama
Route::get('/', [FrontPageController::class, 'index'])->name('home');
Route::get('/breads/{bread}', [FrontPageController::class, 'show'])->name('breads.show');
Route::get('/about', [FrontPageController::class, 'about'])->name('about');

/**
 * Admin - Protected by admin middleware
 * Kumpulan route yang hanya dapat diakses oleh pengguna dengan role `admin`.
 * Middleware `admin` akan memastikan bahwa user:
 * 1. Sudah login (punya session aktif)
 * 2. Memiliki role `admin`
 *
 * Jika tidak memenuhi kedua syarat di atas, user akan diarahkan ke halaman login
 * atau ke halaman utama sesuai dengan middleware `AdminMiddleware`.
 *
 * Prefix       : /admin
 * Middleware   : admin
 * Controller   : App\Http\Controllers\AdminController
 */
Route::middleware(['admin'])->group(function () {

    // Halaman dashboard admin
    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // Tambah kategori roti baru
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])
        ->name('admin.categories.store');

    // Tambah roti baru
    Route::post('/admin/breads', [AdminController::class, 'storeBread'])
        ->name('admin.breads.store');

    // Hapus roti berdasarkan ID
    Route::delete('/admin/breads/{bread}', [AdminController::class, 'destroyBread'])
        ->name('admin.breads.destroy');
});
