<?php
// app/Http/Middleware/VerifyPaymentCallback.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyPaymentCallback
{
    /**
     * Handle an incoming request for payment callbacks
     */
    public function handle(Request $request, Closure $next)
    {
        // Log tất cả callback requests
        Log::info('Payment Callback Request', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $request->headers->all(),
            'data' => $request->all()
        ]);

        // Kiểm tra IP whitelist cho production (tùy chọn)
        if (app()->environment('production')) {
            $allowedIPs = [
                // MoMo IPs
                '118.107.79.0/24',
                '203.162.71.0/24',
                
                // VNPay IPs  
                '113.161.69.0/24',
                '123.30.235.0/24'
            ];

            $requestIP = $request->ip();
            $isAllowed = false;

            foreach ($allowedIPs as $allowedIP) {
                if (strpos($allowedIP, '/') !== false) {
                    // CIDR notation
                    if ($this->ipInRange($requestIP, $allowedIP)) {
                        $isAllowed = true;
                        break;
                    }
                } else {
                    // Single IP
                    if ($requestIP === $allowedIP) {
                        $isAllowed = true;
                        break;
                    }
                }
            }

            if (!$isAllowed) {
                Log::warning('Payment callback from unauthorized IP', ['ip' => $requestIP]);
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        return $next($request);
    }

    private function ipInRange($ip, $range)
    {
        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        return ($ip & $mask) == $subnet;
    }
}

// Đăng ký middleware trong app/Http/Kernel.php
// protected $routeMiddleware = [
//     ...
//     'payment.callback' => \App\Http\Middleware\VerifyPaymentCallback::class,
// ];

// Sử dụng trong routes:
// Route::middleware(['payment.callback'])->group(function () {
//     Route::post('/checkout/momo/ipn', [CheckoutController::class, 'momoIPN']);
//     Route::get('/checkout/momo/return', [CheckoutController::class, 'momoReturn']);
//     Route::get('/checkout/vnpay/return', [CheckoutController::class, 'vnpayReturn']);
// });