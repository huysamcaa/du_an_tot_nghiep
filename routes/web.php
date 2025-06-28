<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\UserAddressController;
use App\Http\Controllers\Client\UserProfileController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\CategoryClientController;
use App\Http\Controllers\Client\CouponController as ClientCouponController;
use CheckoutController as GlobalCheckoutController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;

/*
|--------------------------------------------------------------------------
| 1. Public Routes (Không cần đăng nhập)
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('client.home');

// Trang khuyến mãi

// Chi tiết sản phẩm
Route::get('/product/{id}', [ProductDetailController::class, 'show'])->name('product.detail');

// Route cho việc thêm bình luận (chỉ khi đã mua sản phẩm)
Route::post('/product/{id}/add-comment', [ProductDetailController::class, 'addComment'])->name('product.addComment');
Route::post('/product/{id}/add-reply', [ProductDetailController::class, 'addReply'])->name('product.addReply');
Route::put('/product/{id}/update-comment-or-reply', [ProductDetailController::class, 'updateCommentOrReply'])->name('product.updateCommentOrReply');

// Danh mục sản phẩm (client)
Route::get('/categories', [CategoryClientController::class, 'index'])->name('client.categories.index');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');


Route::middleware(['auth'])->group(function() {
    Route::get('/checkout', [\App\Http\Controllers\Client\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [\App\Http\Controllers\Client\CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::get('/orders/{code}', [\App\Http\Controllers\Client\CheckoutController::class, 'orderDetail'])->name('client.orders.show');
});
// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
// Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Đăng ký & Đăng nhập
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Redirect admin/login và admin/register
Route::get('/admin/login', fn() => redirect()->route('login'))->name('admin.login.redirect');
Route::get('/admin/register', fn() => redirect()->route('register'))->name('admin.register.redirect');

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
    //// Thông tin người dùng
    Route::get('/profile', [UserProfileController::class, 'show'])->name('client.profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('client.profile.edit');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('client.profile.update');
    Route::get('/coupons', [ClientCouponController::class, 'index'])->name('client.coupons.index');
    Route::get('/coupons/active', [ClientCouponController::class, 'active'])->name('client.coupons.active');
    Route::get('/coupons/{id}', [ClientCouponController::class, 'show'])->name('client.coupons.show');

   Route::get('/reviews', [ClientReviewController::class, 'index'])->name('client.reviews.index');
    Route::get('/reviews/{id}/edit', [ClientReviewController::class, 'edit'])->name('client.reviews.edit');
    Route::post('/reviews/{id}/update', [ClientReviewController::class, 'update'])->name('client.reviews.update');
    /*
    |--------------------------------------------------------------------------
    | 3. Admin Routes (Yêu cầu role admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // CRUD chính
        Route::resource('categories', CategoryController::class);
        Route::resource('coupon', CouponController::class);
        Route::resource('products', ProductController::class);
        Route::resource('attributes', AttributeController::class);
        Route::resource('carts', AdminCartController::class);
        Route::resource('comments', CommentController::class);

        // Thêm route cho toggleVisibility
        Route::get('comments/{comment}/toggle', [CommentController::class, 'toggleVisibility'])->name('comments.toggle');
        // Thêm route cho danh sách phản hồi
        Route::get('replies', [CommentController::class, 'indexReplies'])->name('replies.index');

        Route::resource('brands', BrandController::class);
        // Quản lý biến thể sản phẩm
        Route::prefix('products/{product}')->name('products.')->group(function () {
            Route::resource('variants', ProductVariantController::class)->except(['show']);
        });


    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');


        // Quản lý người dùng
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/lock', [UserController::class, 'lock'])->name('users.lock');
        Route::get('/users/locked', [UserController::class, 'locked'])->name('users.locked');
        Route::patch('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');
        // Quản lý đánh giá sản phẩm (admin)
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    });
});
