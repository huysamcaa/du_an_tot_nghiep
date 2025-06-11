<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CategoryClientController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Trang dashboard admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Lấy dữ liệu categories (nếu có)
    // Route::get('/categories/data', [CategoryController::class, 'getData'])->name('categories.data');

    // CRUD danh mục
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
   // web.php
Route::get('category/{id}', [CategoryClientController::class, 'category'])->name('client.category');
Route::get('categories', [CategoryClientController::class, 'index'])->name('client.categories');

});

Route::get('/', [HomeController::class, 'index'])->name('client.home');