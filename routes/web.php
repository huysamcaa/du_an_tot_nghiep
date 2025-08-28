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
use App\Http\Controllers\Admin\BlogController;
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
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\SearchController;
use App\Http\Controllers\Admin\BlogCategoryController;


use App\Http\Controllers\Client\RefundController as ClientRefundController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;

use App\Http\Controllers\Client\BlogController as ClientBlogController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ChangePasswordController;
use CheckoutController as GlobalCheckoutController;
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
/*
|--------------------------------------------------------------------------
| 1. Public Routes (Không cần đăng nhập)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('client.home');

//search
Route::get('/search', [SearchController::class, 'search'])->name('search');


//blogsclient
Route::get('/blogs', [ClientBlogController::class, 'index'])->name('client.blogs.index');
Route::get('/blogs/{slug}', [ClientBlogController::class, 'show'])->name('client.blogs.show');

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

// liên hệ
Route::get('/contact', [ClientContactController::class, 'index'])
    ->name('client.contact.index');

Route::post('/contact', [ClientContactController::class, 'submit'])
    ->name('client.contact.submit');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/contact', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/{id}', [ContactController::class, 'show'])->name('contact.show');
    Route::patch('/contact/{id}/mark-contacted', [ContactController::class, 'markContacted'])->name('contact.markContacted');
});

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/check-variant', [CartController::class, 'checkVariant'])->name('check.variant');
Route::post('/cart/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.deleteSelected');

// Checkout routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
    Route::post('/checkout/coupons/preview', [CheckoutController::class, 'previewCoupon'])
        ->name('checkout.coupons.preview');
    // Payment status and cancel
    Route::get('/payment/status/{orderCode}', [CheckoutController::class, 'checkPaymentStatus'])->name('payment.status');
    Route::post('/payment/cancel', [CheckoutController::class, 'cancelPayment'])->name('payment.cancel');
     Route::get('orders/{order}/change-address', [OrderController::class, 'showChangeAddressForm'])->name('client.orders.change-address-form');
    Route::post('orders/{order}/change-address', [OrderController::class, 'changeAddress'])->name('client.orders.change-address');
});

// MoMo callback routes (không cần auth middleware)
Route::post('/checkout/momo/ipn', [CheckoutController::class, 'momoIPN'])->name('checkout.momo.ipn');
Route::get('/checkout/momo/return', [CheckoutController::class, 'momoReturn'])->name('checkout.momo.return');
Route::post('/checkout/momo/webhook', [CheckoutController::class, 'momoWebhook'])->name('checkout.momo.webhook');

// VNPay callback routes (không cần auth middleware)
Route::get('/checkout/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('checkout.vnpay.return');

// Order routes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders/{code}', [CheckoutController::class, 'orderDetail'])->name('client.orders.show');
    Route::get('/purchase-history', [CheckoutController::class, 'purchaseHistory'])->name('client.orders.history');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('client.orders.cancel');
    Route::post('/orders/{order}/cancel2', [OrderController::class, 'cancel2'])
        ->name('client.orders.cancel2')
        ->middleware('auth');
    Route::get('/orders/{order}/cancel-form', [OrderController::class, 'showCancelForm'])
        ->name('client.orders.cancel-form');
    Route::get('/orders/{order}/cancel-online', [OrderController::class, 'showCancelForm2'])
        ->name('client.orders.cancel-online');
    Route::get('/orders/{order}/cancel-online-refunds', [OrderController::class, 'cancel_online_refunds'])
        ->name('client.orders.cancel-online-refunds');
});
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::get('/purchase-history', [CheckoutController::class, 'purchaseHistory'])->name('client.orders.purchase.history');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');

// Đăng ký & đăng nhập
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Redirect admin/login và admin/register
Route::get('/admin/login', fn() => redirect()->route('login'))->name('admin.login.redirect');
Route::get('/admin/register', fn() => redirect()->route('register'))->name('admin.register.redirect');

Route::get('/change-password', [ChangePasswordController::class, 'showForm'])->middleware('auth')->name('password.change.form');
Route::post('/change-password', [ChangePasswordController::class, 'update'])->middleware('auth')->name('password.change');
// Gửi link reset qua email
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Form nhập mật khẩu mới
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('email/verify-otp', [RegisterController::class, 'showOtpForm'])->name('verification.otp.form');
Route::post('email/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verification.otp.verify');
Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('otp.resend');
/*
|--------------------------------------------------------------------------
| 2. Protected Routes (Yêu cầu đăng nhập)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.user.status'])->group(function () {

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
    Route::get('/profile/change-password', [UserProfileController::class, 'showChangePasswordForm'])
        ->name('client.password.change.form');

    // Coupon
    Route::get('/coupons', [ClientCouponController::class, 'index'])->name('client.coupons.index');
    Route::get('/coupons/received', [ClientCouponController::class, 'received'])->name('client.coupons.received');
    Route::get('/coupons/{id}', [ClientCouponController::class, 'show'])->name('client.coupons.show');
    Route::post('/coupons/{id}/claim', [ClientCouponController::class, 'claim'])->name('client.coupons.claim');



    // Đánh giá của người dùng
    Route::get('/reviews/pending', [ClientReviewController::class, 'pending'])->name('client.reviews.pending');
    Route::get('/reviews', [ClientReviewController::class, 'index'])->name('client.reviews.index');
    Route::post('/reviews', [ClientReviewController::class, 'store'])->name('client.reviews.store');

    // Sản phẩm yêu thích
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'store'])->name('wishlist.add');
    Route::get('/wishlist/destroy/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');



    // Gửi yêu cầu hoàn tiền
    Route::get('/refunds/{order_id}/select-items', [ClientRefundController::class, 'selectItems'])
        ->name('refunds.select_items');

    // Xử lý form chọn sản phẩm, chuyển sang trang create
    Route::post('/refunds/{order_id}/select-items', [ClientRefundController::class, 'confirmItems'])
        ->name('refunds.confirm_items');

    Route::get('/refunds/create/{order_id}/{items}', [ClientRefundController::class, 'create'])->name('refunds.create');
    Route::post('/refunds/store', [ClientRefundController::class, 'store'])->name('refunds.store');
    Route::post('/refunds/{id}/cancel', [ClientRefundController::class, 'cancel'])->name('refunds.cancel');
    Route::post('/orders/{id}/received', [OrderController::class, 'markAsReceived'])
        ->name('client.orders.received')
        ->middleware('auth');

    // Xem danh sách yêu cầu của user
    Route::get('/refunds', [ClientRefundController::class, 'index'])->name('refunds.index');

    // Xem chi tiết yêu cầu
    Route::get('/refunds/{id}', [ClientRefundController::class, 'show'])->name('refunds.show');
    Route::get('/refunds', [ClientRefundController::class, 'index'])
        ->name('refunds.index');
    Route::get('/products', [ProductController::class, 'index'])->name('client.products.index');
    // Route hiển thị thông báo cho người dùng
    // Route danh sách thông báo
    Route::get('/notifications', [NotificationController::class, 'index'])->name('client.notifications.index');

    // Route HÀNH ĐỘNG cụ thể phải đặt TRƯỚC {id}
    Route::patch('/notifications/bulk-read', [NotificationController::class, 'bulkMarkAsRead'])->name('client.notifications.bulkMarkAsRead');
    Route::delete('/notifications/bulk-delete', [NotificationController::class, 'bulkDelete'])->name('client.notifications.bulkDelete');

    // Route xử lý từng thông báo cá nhân
    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('client.notifications.markAsRead');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('client.notifications.show');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('client.notifications.destroy');
});


/*
    |--------------------------------------------------------------------------
    | 3. Admin Routes (Yêu cầu role admin)
    |--------------------------------------------------------------------------
    */

Route::prefix('admin')->name('admin.')->middleware(['admin', 'check.user.status'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
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
    Route::post('comments/{comment}/reply', [AdminCommentController::class, 'storeReply'])->name('comments.reply');
    Route::get('comments/{comment}/toggle', [AdminCommentController::class, 'toggleComment'])->name('comments.toggle');
    Route::get('replies', [AdminCommentController::class, 'indexReplies'])->name('replies.index');
    Route::get('replies/{reply}/toggle', [AdminCommentController::class, 'toggleReply'])->name('replies.toggle');

    Route::delete('coupon/bulk-destroy', [CouponController::class, 'bulkDestroy'])->name('coupon.bulkDestroy');
    Route::post('coupon/bulk-restore',  [CouponController::class, 'bulkRestore'])->name('coupon.bulkRestore');
    Route::get('coupon/trashed',        [CouponController::class, 'trashed'])->name('coupon.trashed');
    Route::post('coupon/{id}/restore',  [CouponController::class, 'restore'])->whereNumber('id')->name('coupon.restore');
    Route::get('coupon/{id}/show',      [CouponController::class, 'show'])->whereNumber('id')->name('coupon.show');
    // routes/web.php (trong group 'admin' -> name('admin.'), đã có coupon.show)
    Route::get('coupon/{id}/claims', [CouponController::class, 'claims'])->name('coupon.claims');   // danh sách đã nhận
    Route::get('coupon/{id}/usages', [CouponController::class, 'usages'])->name('coupon.usages');   // danh sách đã dùng

    // Cuối cùng mới đến resource
    Route::resource('coupon', CouponController::class)->except(['show']);

    Route::delete('brands/bulk-destroy', [BrandController::class, 'bulkDestroy'])->name('brands.bulkDestroy');
    Route::post('brands/bulk-restore',  [BrandController::class, 'bulkRestore'])->name('brands.bulkRestore');
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

    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
        // Route xác nhận hoàn tiền
        Route::get('orders/{order}/confirm-refund', [OrderController::class, 'showConfirmRefund'])
            ->name('orders.confirm-refund');

        Route::post('orders/{order}/confirm-refund', [OrderController::class, 'confirmRefund'])
            ->name('admin.orders.confirm-refund');

        // Route danh sách đơn hàng đã hủy
        Route::get('orders/cancelled', [OrderController::class, 'listCancelledOrders'])
            ->name('orders.cancelled');
    });


    // Quản lý người dùng
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('/users/{user}/lock', [UserController::class, 'lock'])->name('users.lock');
    Route::get('/users/locked', [UserController::class, 'locked'])->name('users.locked');
    Route::patch('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    // Quản lí đánh giá

    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{id}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{id}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');


    // Quản lý hoàn tiền
    Route::get('refunds', [AdminRefundController::class, 'index'])->name('refunds.index');
    Route::get('refunds/{refund}', [AdminRefundController::class, 'show'])->name('refunds.show');
    // routes/web.php
    Route::patch('refunds/{refund}', [AdminRefundController::class, 'update'])
        ->name('refunds.update');



    //Blogs
    Route::resource('blogs', BlogController::class);
    Route::resource('blog_categories', BlogCategoryController::class);
});
