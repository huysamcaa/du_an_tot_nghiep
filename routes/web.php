<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
<<<<<<< HEAD

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CategoryClientController;

=======
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PromotionController as ClientPromotionController;
/*
|--------------------------------------------------------------------------
| Route quản trị KHÔNG yêu cầu đăng nhập
|--------------------------------------------------------------------------
| Chỉ dùng để thử chức năng CRUD. Khi đã cài hệ đăng nhập,
| bạn hãy thêm lại middleware ['auth','is_admin'].
*/
>>>>>>> 1b8339834df09577ef0a780cdab9d0cb3434a38f
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Danh mục
    Route::resource('categories', CategoryController::class);
<<<<<<< HEAD
=======
    Route::resource('manufacturers',ManufacturerController::class);
    Route::resource('promotions',PromotionController::class);
>>>>>>> 1b8339834df09577ef0a780cdab9d0cb3434a38f
    Route::resource('products', ProductController::class);
   // web.php
Route::get('category/{id}', [CategoryClientController::class, 'category'])->name('client.category');
Route::get('categories', [CategoryClientController::class, 'index'])->name('client.categories');

});
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/promotions', [ClientPromotionController::class, 'index'])->name('client.promotions.index');
Route::get('/promotions/{promotion}', [ClientPromotionController::class, 'show'])->name('client.promotions.show');
