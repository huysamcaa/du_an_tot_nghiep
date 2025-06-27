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
use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\PromotionController as ClientPromotionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\UserAddressController;


use App\Http\Controllers\Client\CategoryClientController;

/*
|--------------------------------------------------------------------------
| Route quản trị KHÔNG yêu cầu đăng nhập
|--------------------------------------------------------------------------
| Chỉ dùng để thử chức năng CRUD. Khi đã cài hệ đăng nhập,
| bạn hãy thêm lại middleware ['auth','is_admin'].
*/
// --- 1. Các Route Công khai (Public Routes) ---
// Các route mà bất kỳ ai cũng có thể truy cập (kể cả chưa đăng nhập)


// --- 2. Các Route Cần Xác thực (Authenticated User Routes) ---
// Các route này chỉ có thể truy cập được khi người dùng đã đăng nhập.
// Sử dụng middleware 'auth' đã được định nghĩa trong bootstrap/app.php

    // Dashboard
//     Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

//     // CRUD Danh mục
//     Route::resource('categories', CategoryController::class);
//     Route::resource('manufacturers',ManufacturerController::class);
//     Route::resource('promotions',PromotionController::class);
//     Route::resource('products', ProductController::class);
//     Route::resource('attributes', AttributeController::class);

// Route::prefix('products/{product}')->name('products.')->group(function () {
//         Route::resource('variants', ProductVariantController::class)->except(['show']);
//     });
// });
// Trang chủ chung của ứng dụng (Home của Client)

Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/promotions', [ClientPromotionController::class, 'index'])->name('client.promotions.index');
Route::get('/promotions/{promotion}', [ClientPromotionController::class, 'show'])->name('client.promotions.show');
// Route chi tiết sản phẩm (client)

Route::get('/product/{id}', [ProductDetailController::class, 'show'])->name('product.detail');
// Route danh mục sản phẩm (client)
Route::get('/categories', [CategoryClientController::class, 'index'])->name('client.categories.index');
// Route giỏ hàng (client)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout',[CheckoutController::class, 'index'])->name('checkout');
// Route::post('/checkout',[CheckoutController::class, 'process'])->name('checkout.process');



// Route giỏ hàng (client)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
// Route::get('/checkout',[CheckoutController::class, 'index'])->name('checkout');
// Route::post('/checkout',[CheckoutController::class, 'process'])->name('checkout.process');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Đăng nhập
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Chuyển hướng /admin/login về login chung
Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login.redirect');
Route::get('/admin/register', fn () => redirect()->route('register'))->name('admin.register.redirect');

// --- 2. Route yêu cầu xác thực ---
Route::middleware(['auth'])->group(function () {
    // Đăng xuất
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard người dùng
    Route::get('/home', [HomeController::class, 'index'])->name('user.dashboard');

    // Các route chỉ cho người dùng đã đăng nhập
     Route::resource('my-addresses', UserAddressController::class)->names([
        'index' => 'user.addresses.index',
        'create' => 'user.addresses.create',
        'store' => 'user.addresses.store',
        'edit' => 'user.addresses.edit',
        'update' => 'user.addresses.update',
        'destroy' => 'user.addresses.destroy',

    ]);
    Route::post('/my-addresses/{id}/set-default', [UserAddressController::class, 'setDefault'])->name('user.addresses.set_default');;

    // Các route chỉ cho admin
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // CRUD danh mục, nhà sản xuất, sản phẩm
            Route::resource('categories', CategoryController::class);
            Route::resource('manufacturers',ManufacturerController::class);
            Route::resource('promotions',PromotionController::class);
            Route::resource('products', ProductController::class);
            Route::resource('attributes', AttributeController::class);
            Route::resource('carts', AdminCartController::class);

Route::prefix('products/{product}')->name('products.')->group(function () {
        Route::resource('variants', ProductVariantController::class)->except(['show']);
    });

    });
});


