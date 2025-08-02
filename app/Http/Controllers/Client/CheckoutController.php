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
use Illuminate\Support\Facades\Session;
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
        Log::info('CheckoutController@index - Starting checkout process', ['user_id' => auth()->id()]);

        $userId = auth()->id();
        $selectedItems = $request->input('selected_items', []);

        if (empty($selectedItems)) {
            Log::warning('CheckoutController@index - No items selected for checkout', ['user_id' => $userId]);
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }


        $cartItems = CartItem::with(['product', 'variant'])

            ->where('user_id', $userId)
            ->whereIn('id', $selectedItems)
            ->get();

        if ($cartItems->isEmpty()) {
            Log::error('CheckoutController@index - Cart items not found', [
                'user_id' => $userId,
                'selected_items' => $selectedItems
            ]);
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
        Log::info('CheckoutController@placeOrder - Starting order placement', ['user_id' => auth()->id()]);

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

            // Store order data in session
            $orderData = $this->prepareOrderData($request, $couponData);
            Session::put('pending_order', $orderData);

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

    protected function prepareOrderData(Request $request, $couponData)
    {
        $userId = auth()->id();
        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->whereIn('id', $request->selected_items)
            ->get();

        $total = $this->calculateCartTotal($request->selected_items);
        $discountedTotal = $total - $couponData['discount'];
        $totalAmount = max($discountedTotal + 30000, 0);

        return [
            'user_id' => $userId,
            'payment_id' => $request->paymentMethod,
            'phone_number' => $request->field5,
            'email' => $request->field4,
            'fullname' => $request->field1 . ' ' . $request->field2,
            'address' => $request->field7,
            'note' => $request->field14,
            'total_amount' => $totalAmount,

            'is_paid' => $request->paymentMethod == 2 ? false : false,
            'coupon_id' => $couponData['coupon']?->id,
            'coupon_code' => $couponData['coupon']?->code,
            'coupon_discount' => $couponData['discount'],
            'coupon_discount_type' => $couponData['coupon']?->discount_type,
            'coupon_discount_value' => $couponData['coupon']?->discount_value,
            'cart_items' => $cartItems->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'name' => $item->product->name ?? null,
                    'price' => $item->variant
                        ? ($item->variant->sale_price ?? $item->variant->price)
                        : ($item->product->price ?? 0),
                    'quantity' => $item->quantity ?? 1,
                ];
            })->toArray(),
            'selected_items' => $request->selected_items,
            'coupon_data' => $couponData,
        ];
    }

    protected function processMomoPayment(Request $request, $couponData)
    {
        try {
            $orderData = Session::get('pending_order');
            $totalAmount = $orderData['total_amount'];
            $requestId = time() . "";
            $orderCode = 'DH' . strtoupper(Str::random(8));

            Session::put('momo_order_code', $orderCode);

            $extraData = json_encode([
                'order_code' => $orderCode,
                'user_id' => auth()->id(),
            ]);

            $rawHash = "accessKey=" . $this->momoConfig['accessKey'] .
                      "&amount=" . $totalAmount .
                      "&extraData=" . $extraData .
                      "&ipnUrl=" . route('checkout.momo.ipn') .
                      "&orderId=" . $orderCode .
                      "&orderInfo=" . "Thanh toán đơn hàng #" . $orderCode .
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
                    'orderId' => $orderCode,
                    'orderInfo' => "Thanh toán đơn hàng #" . $orderCode,
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

            return redirect()->away($result['payUrl']);

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi thanh toán MoMo: ' . $e->getMessage());
        }
    }

    protected function processVNPayPayment(Request $request, $couponData)
    {
        try {
            $orderData = Session::get('pending_order');
            $orderCode = 'DH' . strtoupper(Str::random(8));

            // Lưu cả dữ liệu đơn hàng và mã đơn vào session
            Session::put('vnpay_order_data', $orderData);
            Session::put('vnpay_order_code', $orderCode);

            $paymentData = [
                'vnp_Version' => '2.1.0',
                'vnp_TmnCode' => $this->vnpayConfig['vnp_TmnCode'],
                'vnp_Amount' => $orderData['total_amount'] * 100,
                'vnp_Command' => 'pay',
                'vnp_CreateDate' => date('YmdHis'),
                'vnp_CurrCode' => 'VND',
                'vnp_IpAddr' => request()->ip(),
                'vnp_Locale' => 'vn',
                'vnp_OrderInfo' => json_encode([
                    'order_code' => $orderCode,
                    'user_id' => auth()->id(),
                ]),
                'vnp_OrderType' => 'billpayment',
                'vnp_ReturnUrl' => $this->vnpayConfig['vnp_Returnurl'],
                'vnp_TxnRef' => $orderCode,
            ];

            ksort($paymentData);
            $hashData = http_build_query($paymentData);
            $vnpSecureHash = hash_hmac('sha512', $hashData, $this->vnpayConfig['vnp_HashSecret']);
            $paymentData['vnp_SecureHash'] = $vnpSecureHash;

            return redirect()->away($this->vnpayConfig['vnp_Url'] . '?' . http_build_query($paymentData));

        } catch (\Exception $e) {
            return back()->with('error', 'Không thể khởi tạo thanh toán VNPay: ' . $e->getMessage());
        }
    }

    protected function processRegularOrder(Request $request, $couponData)
    {
        DB::beginTransaction();
        try {
            $orderData = Session::get('pending_order');
            $order = $this->saveOrderToDatabase($orderData);
            $this->clearCart(auth()->id());

            DB::commit();
            Session::forget('pending_order');

            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
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
            $orderCode = $extraData['order_code'] ?? null;

            if ($orderCode !== Session::get('momo_order_code')) {
                throw new \Exception('Mã đơn hàng không khớp');
            }

            DB::beginTransaction();

            $orderData = Session::get('pending_order');
            $order = $this->saveOrderToDatabase($orderData);

            $order->update(['is_paid' => 1]);
            $this->reduceStock($order);

            CartItem::where('user_id', $order->user_id)
                ->whereIn('id', $orderData['selected_items'])
                ->delete();

            OrderOrderStatus::create([
                'order_id' => $order->id,
                'order_status_id' => 9,
                'modified_by' => $order->user_id ?? 5,
                'notes' => 'Thanh toán qua MoMo thành công',
            ]);

            DB::commit();

            Session::forget(['pending_order', 'momo_order_code']);

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

            $orderCode = $inputData['vnp_TxnRef'];

            if ($orderCode !== Session::get('vnpay_order_code')) {
                throw new \Exception('Mã đơn hàng không khớp');
            }

            if ($inputData['vnp_ResponseCode'] == '00') {
                DB::beginTransaction();

                // Lấy dữ liệu đơn hàng từ session thay vì database
                $orderData = Session::get('vnpay_order_data');
                $order = $this->saveOrderToDatabase($orderData);

                $order->update(['is_paid' => 1, 'payment_id' => 4]);
                $this->reduceStock($order);

                CartItem::where('user_id', $order->user_id)
                    ->whereIn('id', $orderData['selected_items'])
                    ->delete();

                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => 9,
                    'modified_by' => $order->user_id ?? 5,
                    'notes' => 'Thanh toán qua VNPay thành công',
                ]);

                DB::commit();

                Session::forget(['pending_order', 'vnpay_order_data', 'vnpay_order_code']);

                return redirect()->route('client.orders.show', $order->code)
                    ->with('success', 'Thanh toán VNPay thành công!');
            }

            Session::forget(['pending_order', 'vnpay_order_data', 'vnpay_order_code']);
            return redirect()->route('cart.index')
                ->with('error', 'Thanh toán VNPay thất bại: ' . ($inputData['vnp_ResponseMessage'] ?? ''));

        } catch (\Exception $e) {
            DB::rollBack();
            Session::forget(['pending_order', 'vnpay_order_data', 'vnpay_order_code']);
            return redirect()->route('client.home')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    protected function saveOrderToDatabase($orderData)
    {
        $order = Order::create([
            'code' => 'DH' . strtoupper(Str::random(8)),
            'user_id' => $orderData['user_id'],
            'payment_id' => $orderData['payment_id'],
            'phone_number' => $orderData['phone_number'],
            'email' => $orderData['email'],
            'fullname' => $orderData['fullname'],
            'address' => $orderData['address'],
            'note' => $orderData['note'],
            'total_amount' => $orderData['total_amount'],
            'is_paid' => $orderData['is_paid'],
            'coupon_id' => $orderData['coupon_id'],
            'coupon_code' => $orderData['coupon_code'],
            'coupon_discount' => $orderData['coupon_discount'],
            'coupon_discount_type' => $orderData['coupon_discount_type'],
            'coupon_discount_value' => $orderData['coupon_discount_value'],
        ]);

        OrderOrderStatus::create([
            'order_id' => $order->id,
            'order_status_id' => 1,
            'modified_by' => $order->user_id ?? 5,
        ]);

        foreach ($orderData['cart_items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        return $order;
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

    public function orderDetail($code)
    {
        $order = Order::where('code', $code)->with('items')->firstOrFail();
        return view('client.orders.show', compact('order'));
    }

   public function purchaseHistory()
{
    $userId = auth()->id();

    $orders = Order::where('user_id', $userId)
        ->with([
            'currentStatus.orderStatus',
            'items.product',
            'items.variant'
        ])
        ->orderByDesc('created_at')
        ->paginate(10);

    // Tạo map xác định sản phẩm nào đã đánh giá
    $reviewedMap = [];
    foreach ($orders as $order) {
        foreach ($order->items as $item) {
            if (!$item->product) continue;

            $key = $order->id . '-' . $item->product->id;

            $reviewedMap[$key] = \App\Models\Admin\Review::where('product_id', $item->product->id)
                ->where('order_id', $order->id)
               
                ->where('user_id', auth()->id())
                ->exists();
        }
    }

    return view('client.orders.purchase_history', compact('orders', 'reviewedMap'));

}
