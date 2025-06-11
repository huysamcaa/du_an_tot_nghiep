<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\PromotionController;



/*
|--------------------------------------------------------------------------
| Route quản trị KHÔNG yêu cầu đăng nhập
|--------------------------------------------------------------------------
| Chỉ dùng để thử chức năng CRUD. Khi đã cài hệ đăng nhập,
| bạn hãy thêm lại middleware ['auth','is_admin'].
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Danh mục
    Route::resource('categories', CategoryController::class);
    Route::resource('manufacturers',ManufacturerController::class);
    Route::resource('promotions',PromotionController::class);
});
