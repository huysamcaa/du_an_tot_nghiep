<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Nếu người dùng đã đăng nhập và là admin, chuyển hướng đến admin dashboard
                if (Auth::user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                // Nếu người dùng đã đăng nhập và không phải admin, chuyển hướng đến user dashboard
                return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}
