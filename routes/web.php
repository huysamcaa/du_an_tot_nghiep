<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\PromotionController as ClientPromotionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Route quản trị KHÔNG yêu cầu đăng nhập
|--------------------------------------------------------------------------
| Chỉ dùng để thử chức năng CRUD. Khi đã cài hệ đăng nhập,
| bạn hãy thêm lại middleware ['auth','is_admin'].
*/
// --- 1. Các Route Công khai (Public Routes) ---
// Các route mà bất kỳ ai cũng có thể truy cập (kể cả chưa đăng nhập)

// Trang chủ chung của ứng dụng (Home của Client)
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/promotions', [ClientPromotionController::class, 'index'])->name('client.promotions.index');
Route::get('/promotions/{promotion}', [ClientPromotionController::class, 'show'])->name('client.promotions.show');
Route::get('/product/{id}', [ProductDetailController::class, 'show'])->name('product.detail');

// Routes Đăng ký (dùng chung view auth/register.blade.php)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes Đăng nhập (dùng chung view auth/login.blade.php)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// --- Tùy chọn: Chuyển hướng các URL admin/login về login chung ---
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login.redirect');

Route::get('/admin/register', function () {
    return redirect()->route('register');
})->name('admin.register.redirect');

// --- 2. Các Route Cần Xác thực (Authenticated User Routes) ---
Route::middleware(['auth'])->group(function () {
    // Route Đăng xuất
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard cho người dùng thường (Client)
    Route::get('/home', [HomeController::class, 'index'])->name('user.dashboard');

    // --- 3. Các Route Dành riêng cho Admin (Admin-Only Routes) ---
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('manufacturers', ManufacturerController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('products', ProductController::class);
    });
});
