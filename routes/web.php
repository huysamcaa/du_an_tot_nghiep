<?php


use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;

use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderStatusController;

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\UserAddressController;
use App\Http\Controllers\Client\UserProfileController;
use App\Http\Controllers\Client\ProductDetailController;

use App\Http\Controllers\Client\CouponController as ClientCouponController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;


// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use CheckoutController as GlobalCheckoutController;


/*
|--------------------------------------------------------------------------
| 1. Public Routes (Không cần đăng nhập)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('client.home');

// Bình luận và trả lời bình luận
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/reply', [CommentController::class, 'reply'])->name('comments.reply');
Route::get('/comments/list', [CommentController::class, 'list'])->name('comments.list');

// Chi tiết sản phẩm
Route::get('/product/{id}', [ProductDetailController::class, 'show'])->name('product.detail');

// Thêm và cập nhật bình luận
Route::post('/product/{id}/add-comment', [ProductDetailController::class, 'addComment'])->name('product.addComment');
Route::post('/product/{id}/add-reply', [ProductDetailController::class, 'addReply'])->name('product.addReply');
Route::put('/product/{id}/update-comment-or-reply', [ProductDetailController::class, 'updateCommentOrReply'])->name('product.updateCommentOrReply');

// Danh mục sản phẩm
Route::get('/categories', [ClientCategoryController::class, 'index'])
    ->name('client.categories.index');
// show theo slug
Route::get('/category/{slug}', [ClientCategoryController::class, 'show'])
    ->name('category.show');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/check-variant', [CartController::class, 'checkVariant'])->name('check.variant');
Route::post('/cart/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.deleteSelected');

// Checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::get('/orders/{code}', [CheckoutController::class, 'orderDetail'])->name('client.orders.show');
    Route::post('/checkout/momo', [CheckoutController::class, 'momo_payment'])->name('checkout.momo_payment');
    Route::post('/momo/ipn', [CheckoutController::class, 'momoIPN'])->name('momo.ipn');
    Route::get('/momo/return/{order_code}', [CheckoutController::class, 'momoReturn'])->name('momo.return');
    Route::post('/checkout/vnpay', [CheckoutController::class, 'processVNPayPayment'])->name('checkout.vnpay');
    Route::get('/checkout/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/purchase-history', [CheckoutController::class, 'purchaseHistory'])->name('client.orders.purchase.history');
});

// Đăng ký & đăng nhập
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

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/home', [HomeController::class, 'index'])->name('user.dashboard');

    // Quản lý địa chỉ
    Route::resource('my-addresses', UserAddressController::class)->names([
        'index' => 'user.addresses.index',
        'create' => 'user.addresses.create',
        'store' => 'user.addresses.store',
        'edit' => 'user.addresses.edit',
        'update' => 'user.addresses.update',
        'destroy' => 'user.addresses.destroy',
    ]);
    Route::post('/my-addresses/{id}/set-default', [UserAddressController::class, 'setDefault'])->name('user.addresses.set_default');

    // Thông tin người dùng
    Route::get('/profile', [UserProfileController::class, 'show'])->name('client.profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('client.profile.edit');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('client.profile.update');

    // Coupon
    Route::get('/coupons', [ClientCouponController::class, 'index'])->name('client.coupons.index');
    Route::get('/coupons/active', [ClientCouponController::class, 'active'])->name('client.coupons.active');
    Route::get('/coupons/{id}', [ClientCouponController::class, 'show'])->name('client.coupons.show');
    Route::post('/coupons/{id}/claim', [ClientCouponController::class, 'claim'])->name('client.coupons.claim');

    Route::post('/review', [ClientReviewController::class, 'store'])->name('client.reviews.store');
    Route::get('/my-reviews', [ClientReviewController::class, 'index'])->name('client.reviews.index');

    Route::get('/reviews/create/{order_id}/{product_id}', [ClientReviewController::class, 'create'])->name('client.reviews.create');
    Route::post('/reviews', [ClientReviewController::class, 'store'])->name('client.reviews.store');

    Route::post('/review', [ClientReviewController::class, 'store'])->name('client.reviews.store');
    Route::get('/my-reviews', [ClientReviewController::class, 'index'])->name('client.reviews.index');
});

/*
    |--------------------------------------------------------------------------
    | 3. Admin Routes (Yêu cầu role admin)
    |--------------------------------------------------------------------------
    */


    });

    // Quản lý đơn hàng
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Quản lý người dùng
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('/users/{user}/lock', [UserController::class, 'lock'])->name('users.lock');
    Route::get('/users/locked', [UserController::class, 'locked'])->name('users.locked');
    Route::patch('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');

    // Quản lí đánh giá

    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{id}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');


});
