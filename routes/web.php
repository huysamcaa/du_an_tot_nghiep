<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductDetailController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Trang dashboard admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Lấy dữ liệu categories (nếu có)
    // Route::get('/categories/data', [CategoryController::class, 'getData'])->name('categories.data');

    // CRUD danh mục
    Route::resource('categories', CategoryController::class);
    Route::resource('manufacturers', ManufacturerController::class);
    Route::resource('products', ProductController::class);
});

Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/product/{id}', [ProductDetailController::class, 'show'])->name('product.detail');
