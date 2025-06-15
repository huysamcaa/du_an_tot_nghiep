<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
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
    
// Route giỏ hàng (client)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
// Route::get('/checkout',[CheckoutController::class, 'index'])->name('checkout');
// Route::post('/checkout',[CheckoutController::class, 'process'])->name('checkout.process');
Route::middleware(['auth'])->group(function () {

});

// Routes Đăng ký (dùng chung view auth/register.blade.php)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes Đăng nhập (dùng chung view auth/login.blade.php)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// --- 2. Các Route Cần Xác thực (Authenticated User Routes) ---
// Các route này chỉ có thể truy cập được khi người dùng đã đăng nhập.
// Sử dụng middleware 'auth' đã được định nghĩa trong bootstrap/app.php
Route::middleware(['auth'])->group(function () {
    // Route Đăng xuất
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard cho người dùng thường (Client)
    // DÒNG ĐÃ CHỈNH SỬA: Bây giờ trỏ đến HomeController@index
    Route::get('/home', [HomeController::class, 'index'])->name('user.dashboard');
    
    // --- 3. Các Route Dành riêng cho Admin (Admin-Only Routes) ---
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        // Trang dashboard admin
        // Controller này phải trả về view('admin.dashboard')
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // CRUD danh mục, nhà sản xuất, sản phẩm (ví dụ)
            Route::resource('categories', CategoryController::class);
            Route::resource('manufacturers',ManufacturerController::class);
            Route::resource('promotions',PromotionController::class);
            Route::resource('products', ProductController::class);
            Route::resource('carts', AdminCartController::class);

    });
});

// --- Tùy chọn: Chuyển hướng các URL admin/login về login chung ---
// Điều này ngăn người dùng cố gắng truy cập form login/register riêng cho admin
// và đảm bảo rằng chỉ có một điểm vào xác thực.
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login.redirect');

Route::get('/admin/register', function () {
    return redirect()->route('register');
})->name('admin.register.redirect');
