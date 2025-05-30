<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// Route::middleware(['auth'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'dashboard']);
// });
Route::get('/admin', [AdminController::class, 'dashboard']);
// Route::get('/', [HomeController::class, 'index'])->name('home');