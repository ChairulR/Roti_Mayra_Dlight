<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [FrontPageController::class, 'index'])->name('home');
Route::get('/breads/{bread}', [FrontPageController::class, 'show'])->name('breads.show');
Route::get('/about', [FrontPageController::class, 'about'])->name('about');

//Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{bread}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/add/{bread}', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update')->middleware('auth');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('auth');

// Profile (requires auth)
Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth');

// Admin routes — protect with AdminMiddleware (ensures authenticated admin)
use App\Http\Middleware\AdminMiddleware;

Route::get('/admin', [AdminController::class, 'index'])
	->name('admin.dashboard')
	->middleware(AdminMiddleware::class);

Route::post('/admin/categories', [AdminController::class, 'storeCategory'])
	->name('admin.categories.store')
	->middleware(AdminMiddleware::class);

Route::post('/admin/breads', [AdminController::class, 'storeBread'])
	->name('admin.breads.store')
	->middleware(AdminMiddleware::class);

Route::delete('/admin/breads/{bread}', [AdminController::class, 'destroyBread'])
	->name('admin.breads.destroy')
	->middleware(AdminMiddleware::class);

// Additional admin pages (views) — make sure admin can open add-menu, breads list, categories, and edit pages
Route::get('/admin/add-menu', [AdminController::class, 'addMenu'])
	->name('admin.addmenu')
	->middleware(AdminMiddleware::class);

Route::get('/admin/breads', [AdminController::class, 'breads'])
	->name('admin.breads.index')
	->middleware(AdminMiddleware::class);

Route::get('/admin/categories', [AdminController::class, 'categories'])
	->name('admin.categories.index')
	->middleware(AdminMiddleware::class);

Route::get('/admin/breads/{id}/edit', [AdminController::class, 'editBread'])
	->name('admin.breads.edit')
	->middleware(AdminMiddleware::class);

Route::put('/admin/breads/{id}', [AdminController::class, 'updateBread'])
	->name('admin.breads.update')
	->middleware(AdminMiddleware::class);

// Admin routes — Admin mengatur banner
	Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
		Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
	});

	Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    });


		
//filttering manajemen menu
Route::get('/admin/breads/filter', [AdminController::class, 'breads'])
	->name('admin.breads.filter')
	->middleware(AdminMiddleware::class);

// Hapus kategori
Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory'])
	->name('admin.categories.delete')
	->middleware(AdminMiddleware::class);
