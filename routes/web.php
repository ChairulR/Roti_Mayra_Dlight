<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Front Page (Customer)
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontPageController::class, 'index'])->name('home');
Route::get('/breads/{bread}', [FrontPageController::class, 'show'])->name('breads.show');
Route::get('/about', [FrontPageController::class, 'about'])->name('about');

/*
|--------------------------------------------------------------------------
| Admin Routes (Only for users with role `admin`)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin'])->prefix('admin')->group(function () {

    // Dashboard admin
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // Tambah menu form
    Route::get('/add-menu', [AdminController::class, 'addMenu'])->name('admin.addmenu');

    // Kategori baru
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');

    // Bread CRUD
    Route::get('/breads', [AdminController::class, 'breads'])->name('admin.breads.index');
    Route::post('/breads', [AdminController::class, 'storeBread'])->name('admin.breads.store');
    Route::get('/breads/{bread}/edit', [AdminController::class, 'editBread'])->name('admin.breads.edit');
    Route::put('/breads/{bread}', [AdminController::class, 'updateBread'])->name('admin.breads.update');
    Route::delete('/breads/{bread}', [AdminController::class, 'destroyBread'])->name('admin.breads.destroy');
});
