<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;

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

//Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{bread}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

//Admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
Route::post('/admin/breads', [AdminController::class, 'storeBread'])->name('admin.breads.store');
Route::delete('/admin/breads/{bread}', [AdminController::class, 'destroyBread'])->name('admin.breads.destroy');
