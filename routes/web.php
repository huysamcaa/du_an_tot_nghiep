<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\OrderOrderStatusController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\UserAddressController;
use App\Http\Controllers\Client\UserProfileController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\CouponController as ClientCouponController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use App\Http\Controllers\Client\ReviewController as AdminReviewController;
//  use App\Http\Controllers\Client\ProductController as ClientProductController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use CheckoutController as GlobalCheckoutController;
use App\Http\Controllers\Client\NotificationController;
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
    Route::post('/checkout/momo', [CheckoutController::class, 'processMomoPayment'])->name('checkout.momo');
    Route::get('/checkout/momo/return', [CheckoutController::class, 'momoReturn'])->name('checkout.momo.return');
    Route::post('/checkout/momo/ipn', [CheckoutController::class, 'momoIPN'])->name('checkout.momo.ipn');
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
    Route::get('/coupons/received', [ClientCouponController::class, 'received'])->name('client.coupons.received');
    Route::get('/coupons/{id}', [ClientCouponController::class, 'show'])->name('client.coupons.show');
    Route::post('/coupons/{id}/claim', [ClientCouponController::class, 'claim'])->name('client.coupons.claim');

    Route::post('/review', [ClientReviewController::class, 'store'])->name('client.reviews.store');
    Route::get('/my-reviews', [ClientReviewController::class, 'index'])->name('client.reviews.index');

    Route::get('/reviews/create/{order_id}/{product_id}', [ClientReviewController::class, 'create'])->name('client.reviews.create');
    Route::post('/reviews', [ClientReviewController::class, 'store'])->name('client.reviews.store');
 // Route hiển thị thông báo cho người dùng
    Route::get('/notifications', [NotificationController::class, 'index'])->name('client.notifications.index');
    // Route đánh dấu thông báo đã đọc
    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('client.notifications.markAsRead');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('client.notifications.show');
});

/*
    |--------------------------------------------------------------------------
    | 3. Admin Routes (Yêu cầu role admin)
    |--------------------------------------------------------------------------
    */

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');


    // Categories với chức năng thùng rác
    Route::get('categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::post('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::resource('categories', CategoryController::class);
    // Quản lý sản phẩm
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('admin/products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::delete('admin/products/force-delete/{id}', [ProductController::class, 'forceDelete'])->name('products.forceDelete');

    Route::resource('attributes', AttributeController::class);
    Route::resource('carts', CartController::class);
    Route::resource('comments', AdminCommentController::class);

    // Thêm route cho toggleVisibility
    Route::get('comments/{comment}/toggle', [AdminCommentController::class, 'toggleComment'])->name('comments.toggle');
    Route::get('replies', [AdminCommentController::class, 'indexReplies'])->name('replies.index');
    Route::get('replies/{reply}/toggle', [AdminCommentController::class, 'toggleReply'])->name('replies.toggle');



    Route::resource('coupon', CouponController::class);
    Route::get('brands/trash', [BrandController::class, 'trash'])->name('brands.trash');
    Route::post('brands/restore/{id}', [BrandController::class, 'restore'])->name('brands.restore');
    Route::resource('brands', BrandController::class);

    // Quản lý trạng thái đơn hàng
    Route::resource('order_statuses', OrderStatusController::class);

    // Quản lý biến thể sản phẩm
    Route::prefix('products/{product}')->name('products.')->group(function () {
        Route::resource('variants', ProductVariantController::class)->except(['show']);
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
