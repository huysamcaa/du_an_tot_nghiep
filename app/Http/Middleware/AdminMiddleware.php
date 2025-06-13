<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Cần import Auth facade

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // 2. Kiểm tra vai trò của người dùng đã đăng nhập
        // Đảm bảo rằng User Model của bạn có một cột 'role' và giá trị 'admin'
        if (Auth::user()->role === 'admin') {
            return $next($request); // Cho phép truy cập nếu là admin
        }

        // Nếu không phải admin (hoặc là user thường), chuyển hướng về trang chủ hoặc dashboard user
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
    }
}
