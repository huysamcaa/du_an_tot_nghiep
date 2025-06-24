<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\PromotionController as ClientPromotionController;
use App\Http\Controllers\Client\UserAddressController;
use App\Http\Controllers\Client\CategoryClientController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| 1. Public Routes (Không cần đăng nhập)
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('client.home');

// Trang khuyến mãi
Route::get('/promotions', [ClientPromotionController::class, 'index'])->name('client.promotions.index');
Route::get('/promotions/{promotion}', [ClientPromotionController::class, 'show'])->name('client.promotions.show');

// Chi tiết sản phẩm
Route::get('/product/{id}', [ProductDetailController::class, 'show'])->name('product.detail');

// Danh mục sản phẩm (client)
Route::get('/categories', [CategoryClientController::class, 'index'])->name('client.categories.index');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
// Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Đăng ký & Đăng nhập
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Redirect admin/login và admin/register
Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login.redirect');
Route::get('/admin/register', fn () => redirect()->route('register'))->name('admin.register.redirect');

/*
|--------------------------------------------------------------------------
| 2. Protected Routes (Yêu cầu đăng nhập)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Đăng xuất
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard người dùng
    Route::get('/home', [HomeController::class, 'index'])->name('user.dashboard');

    // Quản lý địa chỉ người dùng
    Route::resource('my-addresses', UserAddressController::class)->names([
        'index' => 'user.addresses.index',
        'create' => 'user.addresses.create',
        'store' => 'user.addresses.store',
        'edit' => 'user.addresses.edit',
        'update' => 'user.addresses.update',
        'destroy' => 'user.addresses.destroy',
    ]);
    Route::post('/my-addresses/{id}/set-default', [UserAddressController::class, 'setDefault'])->name('user.addresses.set_default');

    /*
    |--------------------------------------------------------------------------
    | 3. Admin Routes (Yêu cầu role admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // CRUD chính
        Route::resource('categories', CategoryController::class);
        Route::resource('manufacturers', ManufacturerController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('products', ProductController::class);
        Route::resource('attributes', AttributeController::class);
        Route::resource('carts', AdminCartController::class);

        // Quản lý biến thể sản phẩm
        Route::prefix('products/{product}')->name('products.')->group(function () {
            Route::resource('variants', ProductVariantController::class)->except(['show']);
        });

        // Quản lý người dùng
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/lock', [UserController::class, 'lock'])->name('users.lock');
        Route::get('/users/locked', [UserController::class, 'locked'])->name('users.locked');
        Route::patch('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');
    });
});
