<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Trang dashboard admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Lấy dữ liệu categories (nếu có)
    // Route::get('/categories/data', [CategoryController::class, 'getData'])->name('categories.data');

    // CRUD danh mục
    Route::resource('categories', CategoryController::class);
});