<?php

namespace App\Http\Controllers\Client;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    // GET /coupons
    public function index()
    {
        $user = Auth::user();
        $coupons = $user->coupons()->with('restriction')->get();

        return view('client.coupons.index', compact('coupons'));
    }

    // GET /coupons/active
    public function active()
    {
        $coupons = Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.coupons.active', compact('coupons'));
    }

    // GET /coupons/{id}
    public function show($id)
    {
        $coupon = Coupon::with('restriction')
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return view('client.coupons.show', compact('coupon'));
    }

    
}
