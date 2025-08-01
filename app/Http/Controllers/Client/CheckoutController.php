<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin\CartItem;
use App\Models\Admin\OrderOrderStatus;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\Client\UserAddress;
use App\Models\Coupon;
use App\Models\CouponRestriction;
use App\Models\Shared\Order;
use App\Models\Shared\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Cấu hình MoMo
    private $momoConfig = [
        'endpoint'    => 'https://test-payment.momo.vn/v2/gateway/api/create',
        'partnerCode' => 'MOMOBKUN20180529',
        'accessKey'   => 'klm05TvNBzhg7h7j',
        'secretKey'   => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
        'requestType' => 'payWithATM',
    ];

    // Cấu hình VNPay
    private $vnpayConfig = [
        'vnp_Url'        => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'vnp_TmnCode'    => 'PBDJFA7H',
        'vnp_HashSecret' => 'ANBVL0AXYOROIENQ5A945WKXIATVQ3KL',
        'vnp_Returnurl'  => 'http://localhost:8000/checkout/vnpay/return',
    ];

    public function index(Request $request)
    {
        $userId = auth()->id();
        $selectedItems = $request->input('selected_items', []);

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }

        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->whereIn('id', $selectedItems)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sản phẩm bạn chọn không tồn tại trong giỏ hàng.');
        }

        $total = $this->calculateCartTotal($selectedItems);
        $coupons = $this->getAvailableCoupons();

        return view('client.checkout.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => auth()->user(),
            'addresses' => UserAddress::where('user_id', $userId)->orderBy('id_default', 'DESC')->get(),
            'defaultAddress' => UserAddress::where('user_id', $userId)->where('id_default', 1)->first(),
            'coupons' => $coupons,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->status === 'locked') {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa, không thể đặt hàng!');
        }

        $request->validate([
            'field1' => 'required|string|max:255',
            'field2' => 'required|string|max:255',
            'field4' => 'required|email|max:255',
            'field5' => 'required|string|max:20',
            'field7' => 'required|string|max:255',
            'paymentMethod' => 'required|in:1,2,3,4',
            'selected_items' => 'required|array',
            'coupon_code' => 'nullable|string',
        ]);

        try {
            $total = $this->calculateCartTotal($request->selected_items);
            $couponData = $this->processCoupon($request->coupon_code, $total);

            if ($request->paymentMethod == 3) {
                return $this->processMomoPayment($request, $couponData);
            } elseif ($request->paymentMethod == 4) {
                return $this->processVNPayPayment($request, $couponData);
            }

            return $this->processRegularOrder($request, $couponData);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    protected function processMomoPayment(Request $request, $couponData)
    {
        try {
            $userId = auth()->id();
            $total = $this->calculateCartTotal($request->selected_items);
            $discountedTotal = $total - $couponData['discount'];
            $shippingFee = 30000;
            $totalAmount = $discountedTotal + $shippingFee;

            DB::beginTransaction();

            $order = $this->createOrder($request, 3, false, $couponData);

            $requestId = time() . "";
            $orderInfo = "Thanh toán đơn hàng #" . $order->code;
            $extraData = json_encode([
                'order_id' => $order->id,
                'user_id' => $userId,
                'cart_items' => $request->selected_items,
                'coupon_code' => $request->coupon_code
            ]);

            $rawHash = "accessKey=" . $this->momoConfig['accessKey'] . 
                      "&amount=" . $totalAmount . 
                      "&extraData=" . $extraData . 
                      "&ipnUrl=" . route('checkout.momo.ipn') . 
                      "&orderId=" . $order->code . 
                      "&orderInfo=" . $orderInfo . 
                      "&partnerCode=" . $this->momoConfig['partnerCode'] . 
                      "&redirectUrl=" . route('checkout.momo.return') . 
                      "&requestId=" . $requestId . 
                      "&requestType=" . $this->momoConfig['requestType'];

            $signature = hash_hmac("sha256", $rawHash, $this->momoConfig['secretKey']);

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($this->momoConfig['endpoint'], [
                    'partnerCode' => $this->momoConfig['partnerCode'],
                    'partnerName' => "Test Merchant",
                    'storeId' => "store001",
                    'requestId' => $requestId,
                    'amount' => $totalAmount,
                    'orderId' => $order->code,
                    'orderInfo' => $orderInfo,
                    'redirectUrl' => route('checkout.momo.return'),
                    'ipnUrl' => route('checkout.momo.ipn'),
                    'lang' => 'vi',
                    'extraData' => $extraData,
                    'requestType' => $this->momoConfig['requestType'],
                    'signature' => $signature
                ]);

            $result = $response->json();

            if (!isset($result['payUrl'])) {
                throw new \Exception($result['message'] ?? 'Không thể khởi tạo thanh toán MoMo');
            }

            DB::commit();
            return redirect()->away($result['payUrl']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MoMo Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Lỗi thanh toán MoMo: ' . $e->getMessage());
        }
    }

    protected function processVNPayPayment(Request $request, $couponData)
    {
        try {
            DB::beginTransaction();
            $order = $this->createOrder($request, 4, false, $couponData);

            $paymentData = [
                'vnp_Version' => '2.1.0',
                'vnp_TmnCode' => $this->vnpayConfig['vnp_TmnCode'],
                'vnp_Amount' => $order->total_amount * 100,
                'vnp_Command' => 'pay',
                'vnp_CreateDate' => date('YmdHis'),
                'vnp_CurrCode' => 'VND',
                'vnp_IpAddr' => request()->ip(),
                'vnp_Locale' => 'vn',
                'vnp_OrderInfo' => json_encode([
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'cart_items' => $request->selected_items,
                    'coupon_code' => $request->coupon_code
                ]),
                'vnp_OrderType' => 'billpayment',
                'vnp_ReturnUrl' => $this->vnpayConfig['vnp_Returnurl'],
                'vnp_TxnRef' => $order->code,
            ];

            ksort($paymentData);
            $hashData = http_build_query($paymentData);
            $vnpSecureHash = hash_hmac('sha512', $hashData, $this->vnpayConfig['vnp_HashSecret']);
            $paymentData['vnp_SecureHash'] = $vnpSecureHash;

            DB::commit();
            return redirect()->away($this->vnpayConfig['vnp_Url'] . '?' . http_build_query($paymentData));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Không thể khởi tạo thanh toán VNPay: ' . $e->getMessage());
        }
    }

    protected function processRegularOrder(Request $request, $couponData)
    {
        DB::beginTransaction();
        try {
            $order = $this->createOrder($request, $request->paymentMethod, true, $couponData);
            $this->clearCart(auth()->id());

            DB::commit();
            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Place order error', ['error' => $e]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    protected function createOrder(Request $request, $paymentMethod, $reduceStock, $couponData)
    {
        $userId = auth()->id();
        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->whereIn('id', $request->selected_items)
            ->get();

        $total = $this->calculateCartTotal($request->selected_items);
        $discountedTotal = $total - $couponData['discount'];
        $totalAmount = max($discountedTotal + 30000, 0); // Phí vận chuyển 30,000đ

        $order = Order::create([
            'code' => 'DH' . strtoupper(Str::random(8)),
            'user_id' => $userId,
            'payment_id' => $paymentMethod,
            'phone_number' => $request->field5,
            'email' => $request->field4,
            'fullname' => $request->field1 . ' ' . $request->field2,
            'address' => $request->field7,
            'note' => $request->field14,
            'total_amount' => $totalAmount,
            'is_paid' => $paymentMethod == 2 ? false : false,
            'coupon_id' => $couponData['coupon']?->id,
            'coupon_code' => $couponData['coupon']?->code,
            'coupon_discount' => $couponData['discount'],
            'coupon_discount_type' => $couponData['coupon']?->discount_type,
            'coupon_discount_value' => $couponData['coupon']?->discount_value,
        ]);

        OrderOrderStatus::create([
            'order_id' => $order->id,
            'order_status_id' => 1,
            'modified_by' => $order->user_id ?? 5,
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

        if ($reduceStock) {
            $this->reduceStock($order);
        }

        return $order;
    }

    protected function calculateCartTotal($selectedItems)
    {
        return CartItem::with(['product', 'variant'])
            ->where('user_id', auth()->id())
            ->whereIn('id', $selectedItems)
            ->get()
            ->sum(function ($item) {
                $price = $item->variant 
                    ? ($item->variant->sale_price ?? $item->variant->price)
                    : ($item->product->price ?? 0);
                return $price * $item->quantity;
            });
    }

    protected function getAvailableCoupons()
    {
        return Coupon::where('is_active', 1)
            ->where(function ($query) {
                $query->where('is_expired', 0)
                      ->orWhere('end_date', '>=', now());
            })
            ->get();
    }

    protected function processCoupon($couponCode, $total)
    {
        if (!$couponCode) {
            return ['discount' => 0, 'coupon' => null];
        }

        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', 1)
            ->where(function($q) {
                $q->where('is_expired', 0)
                  ->orWhere('end_date', '>=', now());
            })->first();

        if (!$coupon) {
            throw new \Exception('Mã giảm giá không hợp lệ hoặc đã hết hạn');
        }

        $restriction = CouponRestriction::where('coupon_id', $coupon->id)->first();

        if ($restriction && $restriction->min_order_value > $total) {
            throw new \Exception('Đơn hàng chưa đủ điều kiện áp dụng mã giảm giá');
        }

        $discount = $coupon->discount_type === 'percent'
            ? $total * ($coupon->discount_value / 100)
            : $coupon->discount_value;

        if ($restriction && $restriction->max_discount_value) {
            $discount = min($discount, $restriction->max_discount_value);
        }

        return [
            'discount' => $discount,
            'coupon' => $coupon
        ];
    }

    protected function reduceStock($order)
    {
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                ProductVariant::where('id', $item->product_variant_id)
                    ->decrement('stock', $item->quantity);
            } else {
                Product::where('id', $item->product_id)
                    ->decrement('stock', $item->quantity);
            }
        }
    }

    protected function clearCart($userId)
    {
        CartItem::where('user_id', $userId)->delete();
    }

    public function momoReturn(Request $request)
    {
        try {
            if ($request->resultCode != 0) {
                return redirect()->route('cart.index')
                    ->with('error', 'Thanh toán thất bại: ' . ($request->message ?? ''));
            }

            if (!$this->verifyMomoSignature($request->all())) {
                throw new \Exception('Chữ ký không hợp lệ');
            }

            $extraData = json_decode($request->extraData, true);
            $order = Order::findOrFail($extraData['order_id']);

            DB::beginTransaction();

            if (!$order->is_paid) {
                $order->update(['is_paid' => 1]);

                $this->reduceStock($order);

                if (isset($extraData['cart_items'])) {
                    CartItem::where('user_id', $extraData['user_id'])
                        ->whereIn('id', $extraData['cart_items'])
                        ->delete();
                }

                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => 9,
                    'modified_by' => $order->user_id ?? 5,
                    'notes' => 'Thanh toán qua MoMo thành công',
                ]);
            }

            DB::commit();
            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Thanh toán thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    public function vnpayReturn(Request $request)
    {
        try {
            $inputData = $request->all();
            $vnp_SecureHash = $inputData['vnp_SecureHash'];
            unset($inputData['vnp_SecureHash']);

            ksort($inputData);
            $hashData = http_build_query($inputData);
            $secureHash = hash_hmac('sha512', $hashData, $this->vnpayConfig['vnp_HashSecret']);

            if ($secureHash != $vnp_SecureHash) {
                throw new \Exception('Chữ ký không hợp lệ');
            }

            $order = Order::where('code', $inputData['vnp_TxnRef'])->firstOrFail();

            if ($inputData['vnp_ResponseCode'] == '00') {
                DB::beginTransaction();

                if (!$order->is_paid) {
                    $order->update(['is_paid' => 1, 'payment_id' => 4]);

                    $this->reduceStock($order);

                    $orderInfo = json_decode($inputData['vnp_OrderInfo'], true);
                    if (isset($orderInfo['cart_items'])) {
                        CartItem::where('user_id', $order->user_id)
                            ->whereIn('id', $orderInfo['cart_items'])
                            ->delete();
                    }

                    OrderOrderStatus::create([
                        'order_id' => $order->id,
                        'order_status_id' => 9,
                        'modified_by' => $order->user_id ?? 5,
                        'notes' => 'Thanh toán qua VNPay thành công',
                    ]);
                }

                DB::commit();
                return redirect()->route('client.orders.show', $order->code)
                    ->with('success', 'Thanh toán VNPay thành công!');
            }

            return redirect()->route('client.orders.show', $order->code)
                ->with('error', 'Thanh toán VNPay thất bại: ' . ($inputData['vnp_ResponseMessage'] ?? ''));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('client.home')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }
    }

    protected function verifyMomoSignature($params)
    {
        $requiredFields = [
            'partnerCode', 'orderId', 'requestId', 'amount',
            'orderInfo', 'orderType', 'transId', 'resultCode',
            'message', 'payType', 'responseTime', 'extraData',
            'signature'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($params[$field])) {
                return false;
            }
        }

        $rawHash = "accessKey=" . $this->momoConfig['accessKey'] .
            "&amount=" . $params['amount'] .
            "&extraData=" . $params['extraData'] .
            "&message=" . $params['message'] .
            "&orderId=" . $params['orderId'] .
            "&orderInfo=" . $params['orderInfo'] .
            "&orderType=" . $params['orderType'] .
            "&partnerCode=" . $params['partnerCode'] .
            "&payType=" . $params['payType'] .
            "&requestId=" . $params['requestId'] .
            "&responseTime=" . $params['responseTime'] .
            "&resultCode=" . $params['resultCode'] .
            "&transId=" . $params['transId'];

        $computedSignature = hash_hmac('sha256', $rawHash, $this->momoConfig['secretKey']);
        return hash_equals($computedSignature, $params['signature']);
    }

    public function orderDetail($code)
    {
        $order = Order::where('code', $code)->with('items')->firstOrFail();
        return view('client.orders.show', compact('order'));
    }

   public function purchaseHistory()
{
    $orders = Order::where('user_id', auth()->id())
        ->with([
            'currentStatus.orderStatus', // Quan hệ hiện tại
            'items.product', // Sửa từ 'orderItems' thành 'items' để khớp với model
            'items.variant' // Sử dụng 'variant' thay vì 'variation' nếu cần
        ])
        ->orderByDesc('created_at')
        ->paginate(10);
         $statusName = $order->currentStatus->orderStatus->name ?? 'Không xác định';

    return view('client.orders.purchase_history', compact('orders'));
}
}