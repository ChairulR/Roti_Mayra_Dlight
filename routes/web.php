<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\AdminController;

//Dashboard Utama
Route::get('/', [FrontPageController::class, 'index'])->name('home');
Route::get('/breads/{bread}', [FrontPageController::class, 'show'])->name('breads.show');
Route::get('/about', [FrontPageController::class, 'about'])->name('about');

//Admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
Route::post('/admin/breads', [AdminController::class, 'storeBread'])->name('admin.breads.store');
Route::delete('/admin/breads/{bread}', [AdminController::class, 'destroyBread'])->name('admin.breads.destroy');
