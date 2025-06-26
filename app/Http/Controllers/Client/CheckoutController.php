<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CartItem;
use App\Models\Shared\Order;
use App\Models\Shared\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 2; // hoặc giả định 2 cho test

        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return ($item->product ? $item->product->price : 0) * $item->quantity;
        });

        return view('client.checkout.checkout', compact('cartItems', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $userId = auth()->id() ?? 2;
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng trống!');
        }

        $request->validate([
            'field1' => 'required|string|max:255', // First Name
            'field2' => 'required|string|max:255', // Last Name
            'field4' => 'required|email|max:255',  // Email
            'field5' => 'required|string|max:20',  // Phone
            'field7' => 'required|string|max:255', // Address
            'paymentMethod' => 'required|in:1,2,3,4',
        ]);

        DB::beginTransaction();
        try {
            $fullname = $request->input('field1') . ' ' . $request->input('field2');
            $total = $cartItems->sum(function($item) {
                return ($item->product ? $item->product->price : 0) * $item->quantity;
            });
            $shipping_fee = 30000;
            $grand_total = $total + $shipping_fee;

            $order = Order::create([
                'code' => 'DH' . strtoupper(Str::random(8)),
                'user_id' => $userId,
                'payment_id' => $request->input('paymentMethod'),
                'phone_number' => $request->input('field5'),
                'email' => $request->input('field4'),
                'fullname' => $fullname,
                'address' => $request->input('field7'),
                'note' => $request->input('field14'),
                'total_amount' => $grand_total,
                'is_paid' => false,
                'is_refund' => false,
                'locked_status' => false,
                'coupon_code' => $request->input('coupon_code'),
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'name' => $item->product->name ?? null,
                    'price' => $item->product->price ?? 0,
                    'quantity' => $item->quantity ?? 1,
                ]);
            }

            CartItem::where('user_id', $userId)->delete();

            DB::commit();
            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Đặt hàng thành công! Đơn hàng sẽ được giao và thanh toán khi nhận hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function orderDetail($code)
    {
        $order = Order::where('code', $code)->with('items')->firstOrFail();
        return view('client.orders.show', compact('order'));
    }
}