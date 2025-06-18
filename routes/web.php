<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PromotionController as ClientPromotionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;

// --- 1. Các Route Công khai (Public Routes) ---
Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/promotions', [ClientPromotionController::class, 'index'])->name('client.promotions.index');
Route::get('/promotions/{promotion}', [ClientPromotionController::class, 'show'])->name('client.promotions.show');

// Routes Đăng ký
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes Đăng nhập
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// --- 2. Các Route Cần Xác thực ---
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/home', [HomeController::class, 'index'])->name('user.dashboard');

    // --- 3. Các Route Dành riêng cho Admin ---
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // CRUD chính
        Route::resource('categories', CategoryController::class);
        Route::resource('manufacturers', ManufacturerController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('products', ProductController::class);

        // ➕ Gộp từ nhánh Phạm-Tiến-Đức
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/lock', [UserController::class, 'lock'])->name('users.lock');
        Route::get('/users/locked', [UserController::class, 'locked'])->name('users.locked');
        Route::patch('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');

        // ➕ Gộp từ nhánh main
        Route::resource('attributes', AttributeController::class);
        Route::prefix('products/{product}')->name('products.')->group(function () {
            Route::resource('variants', ProductVariantController::class)->except(['show']);
        });
    });
});

// --- Redirect admin/login và admin/register ---
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login.redirect');

Route::get('/admin/register', function () {
    return redirect()->route('register');
})->name('admin.register.redirect');
