<?php

    namespace App\Http\Controllers\Client;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Log;
    use App\Models\Admin\CartItem;
    use App\Models\Client\UserAddress;
    use App\Models\Admin\Product;
    use App\Models\Admin\ProductVariant;
    use App\Models\Shared\Order;
    use App\Models\Shared\OrderItem;
    use App\Models\Coupon;
    use App\Models\CouponRestriction;
    use App\Models\Admin\OrderOrderStatus;
    use Illuminate\Support\Facades\Auth;

    class CheckoutController extends Controller
    {
        // Cấu hình MoMo
        private $momoConfig = [
            'endpoint' => 'https://test-payment.momo.vn/v2/gateway/api/create',
            'partnerCode' => 'MOMOBKUN20180529',
            'accessKey' => 'klm05TvNBzhg7h7j',
            'secretKey' => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
            'requestType' => 'payWithATM'
        ];

        // Cấu hình VNPay
        private $vnpayConfig = [
            'vnp_Url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
            'vnp_TmnCode' => 'PBDJFA7H',
            'vnp_HashSecret' => 'ANBVL0AXYOROIENQ5A945WKXIATVQ3KL',
            'vnp_Returnurl' => 'http://localhost:8000/checkout/vnpay/return'
        ];

        public function index(Request $request)
    {
        $userId = auth()->id();
        $user = auth()->user();
        $selectedItems = $request->input('selected_items', []);

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }

        $cartItems = CartItem::where('user_id', $userId)
            ->whereIn('id', $selectedItems)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sản phẩm bạn chọn không tồn tại trong giỏ hàng.');
        }

        $total = $cartItems->sum(function ($item) {
            return ($item->product ? $item->product->price : 0) * $item->quantity;
        });

        // Lấy địa chỉ mặc định của user
        $defaultAddress = DB::table('user_addresses')
            ->where('user_id', $userId)
            ->where('id_default', 1)
            ->first();

        // Hoặc lấy địa chỉ đầu tiên nếu không có mặc định
        if (!$defaultAddress) {
            $defaultAddress = DB::table('user_addresses')
                ->where('user_id', $userId)
                ->first();
        }
        return view('client.checkout.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => $user,
            'defaultAddress' => $defaultAddress
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
            'paymentMethod' => 'required|in:1,2,3,4', // 1: Bank, 2: COD, 3: MoMo, 4: VNPay
        ]);

        if ($request->paymentMethod == 3) {
            return $this->processMomoPayment($request);
        } elseif ($request->paymentMethod == 4) {
            return $this->processVNPayPayment($request);
        }

        return $this->processRegularOrder($request);
    }

        // Xử lý thanh toán MoMo
        protected function processMomoPayment(Request $request)
{
    $userId = auth()->id();
    $selectedItems = $request->input('selected_items', []);

    if (empty($selectedItems)) {
        return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm');
    }

    $cartItems = CartItem::where('user_id', $userId)
                ->whereIn('id', $selectedItems)
                ->with('product')
                ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Giỏ hàng trống');
    }

    // Tính toán tổng tiền
    $total = $cartItems->sum(function($item) {
        return ($item->product->price ?? 0) * $item->quantity;
    });
    $shippingFee = 30000;
    $totalAmount = $total + $shippingFee;

    try {
        $orderId = 'MOMO_'.time().'_'.Str::random(5);

        $paymentData = [
            'partnerCode' => $this->momoConfig['partnerCode'],
            'partnerName' => "Your Shop",
            'requestId' => time().'',
            'amount' => $totalAmount,
            'orderId' => $orderId,
            'orderInfo' => "Thanh toán đơn hàng ".$orderId,
            'redirectUrl' => route('momo.return'),
            'ipnUrl' => route('momo.ipn'),
            'lang' => 'vi',
            'requestType' => $this->momoConfig['requestType'],
            'extraData' => json_encode([
                'user_id' => $userId,
                'cart_items' => $selectedItems,
                'order_data' => [
                    'total_amount' => $totalAmount,
                    'items' => $cartItems->map(function($item) {
                        return [
                            'product_id' => $item->product_id,
                            'variant_id' => $item->product_variant_id,
                            'quantity' => $item->quantity,
                            'price' => $item->product->price ?? 0
                        ];
                    })->toArray()
                ]
            ])
        ];

        // Tạo chữ ký
        $rawHash = "accessKey=".$this->momoConfig['accessKey'].
                   "&amount=".$totalAmount.
                   "&extraData=".$paymentData['extraData'].
                   "&ipnUrl=".$paymentData['ipnUrl'].
                   "&orderId=".$orderId.
                   "&orderInfo=".$paymentData['orderInfo'].
                   "&partnerCode=".$this->momoConfig['partnerCode'].
                   "&redirectUrl=".$paymentData['redirectUrl'].
                   "&requestId=".$paymentData['requestId'].
                   "&requestType=".$this->momoConfig['requestType'];

        $paymentData['signature'] = hash_hmac('sha256', $rawHash, $this->momoConfig['secretKey']);

        // Gửi request đến MoMo
        $response = Http::post($this->momoConfig['endpoint'], $paymentData);
        $result = $response->json();

        if (isset($result['payUrl'])) {
            return redirect()->away($result['payUrl']);
        }

        throw new \Exception($result['message'] ?? 'Không thể khởi tạo thanh toán MoMo');

    } catch (\Exception $e) {
        Log::error('MoMo Payment Error: '.$e->getMessage());
        return back()->with('error', 'Lỗi thanh toán MoMo: '.$e->getMessage());
    }
}
    protected function prepareOrderData(Request $request, $cartItems)
    {
        $total = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        $shippingFee = 30000;

        return [
            'user_id' => auth()->id(),
            'payment_id' => 3, // MoMo
            'fullname' => $request->field1.' '.$request->field2,
            'email' => $request->field4,
            'phone_number' => $request->field5,
            'address' => $request->field7,
            'total_amount' => $total + $shippingFee,
            'items' => $cartItems->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ];
            })->toArray()
        ];
    }

        protected function prepareMomoPaymentData($order)
        {
            return [
                'partnerCode' => $this->momoConfig['partnerCode'],
                'partnerName' => "Your Shop Name",
                'storeId' => "MomoTestStore",
                'requestId' => time() . "",
                'amount' => $order->total_amount,
                'orderId' => $order->code,
                'orderInfo' => "Thanh toán đơn hàng #" . $order->code,
                'redirectUrl' => route('momo.return', ['order_code' => $order->code]),
                'ipnUrl' => route('momo.ipn'),
                'lang' => 'vi',
                'extraData' => json_encode(['order_id' => $order->id]),
                'requestType' => $this->momoConfig['requestType'],
                'signature' => $this->generateMomoSignature($order)
            ];
        }

        protected function generateMomoSignature($order)
        {
            $rawHash = "accessKey=" . $this->momoConfig['accessKey'] .
                "&amount=" . $order->total_amount .
                "&extraData=" . json_encode(['order_id' => $order->id]) .
                "&ipnUrl=" . route('momo.ipn') .
                "&orderId=" . $order->code .
                "&orderInfo=Thanh toán đơn hàng #" . $order->code .
                "&partnerCode=" . $this->momoConfig['partnerCode'] .
                "&redirectUrl=" . route('momo.return', ['order_code' => $order->code]) .
                "&requestId=" . time() .
                "&requestType=" . $this->momoConfig['requestType'];

            return hash_hmac("sha256", $rawHash, $this->momoConfig['secretKey']);
        }

        protected function sendMomoRequest($data)
        {
            $ch = curl_init($this->momoConfig['endpoint']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }

        public function momoReturn(Request $request)
    {
        try {
            if ($request->resultCode != 0) {
                return redirect()->route('cart.index')
                    ->with('error', 'Thanh toán thất bại: '.$request->message);
            }

            // Xác thực chữ ký
            if (!$this->verifyMomoSignature($request->all())) {
                throw new \Exception('Chữ ký không hợp lệ');
            }

            $extraData = json_decode($request->extraData, true);

            DB::beginTransaction();

            // Tạo đơn hàng thực sự
            $order = Order::create([
                'code' => 'DH'.strtoupper(Str::random(8)),
                'user_id' => $extraData['user_id'],
                'payment_id' => 3, // MoMo
                'phone_number' => $extraData['order_data']['phone_number'],
                'email' => $extraData['order_data']['email'],
                'fullname' => $extraData['order_data']['fullname'],
                'address' => $extraData['order_data']['address'],
                'total_amount' => $extraData['order_data']['total_amount'],
                'is_paid' => true
            ]);

            // Thêm sản phẩm vào đơn hàng
            foreach ($extraData['order_data']['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Trừ số lượng tồn kho
                if ($item['variant_id']) {
                    ProductVariant::where('id', $item['variant_id'])
                        ->decrement('quantity', $item['quantity']);
                } else {
                    Product::where('id', $item['product_id'])
                        ->decrement('quantity', $item['quantity']);
                }
            }

            // Xóa giỏ hàng
            CartItem::where('user_id', $extraData['user_id'])
                    ->whereIn('id', $extraData['cart_items'])
                    ->delete();

            DB::commit();

            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Thanh toán thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MoMo Return Error: '.$e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi xử lý đơn hàng: '.$e->getMessage());
        }
    }

        public function momoIPN(Request $request)
{
    DB::beginTransaction();
    try {
        $input = $request->all();
        Log::channel('momo')->info('Raw IPN Data:', $request->all());
        // Log toàn bộ dữ liệu nhận được từ MoMo
        Log::channel('momo')->info('MoMo IPN received', $input);

        // 1. Kiểm tra các trường bắt buộc
        $requiredFields = ['resultCode', 'orderId', 'amount', 'extraData', 'signature'];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                throw new \Exception("Thiếu trường bắt buộc: $field");
            }
        }

        // 2. Xác thực chữ ký
        if (!$this->verifyMomoSignature($input)) {
            Log::channel('momo')->error('Invalid MoMo signature', [
                'input' => $input,
                'computed_signature' => $this->computeMomoSignature($input)
            ]);
            throw new \Exception('Chữ ký không hợp lệ');
        }

        // 3. Chỉ xử lý khi thanh toán thành công
        if ($input['resultCode'] != 0) {
            Log::channel('momo')->warning('MoMo payment failed', [
                'orderId' => $input['orderId'],
                'message' => $input['message'] ?? null
            ]);
            return response()->json(['success' => false, 'message' => 'Payment failed']);
        }

        // 4. Giải mã extraData
        $extraData = json_decode($input['extraData'], true);
        if (!$extraData || !isset($extraData['user_id'], $extraData['order_data'])) {
            throw new \Exception('Dữ liệu extraData không hợp lệ');
        }

        // 5. Kiểm tra tránh xử lý trùng lặp
        $existingOrder = Order::where('code', $input['orderId'])->first();
        if ($existingOrder) {
            Log::channel('momo')->info('Order already processed', [
                'orderId' => $input['orderId'],
                'orderStatus' => $existingOrder->is_paid
            ]);
            return response()->json(['success' => true, 'message' => 'Order already processed']);
        }

        // 6. Tạo đơn hàng
        $orderData = [
            'code' => $input['orderId'],
            'user_id' => $extraData['user_id'],
            'payment_id' => 3, // MoMo
            'phone_number' => $extraData['order_data']['phone_number'] ?? null,
            'email' => $extraData['order_data']['email'] ?? null,
            'fullname' => $extraData['order_data']['fullname'] ?? null,
            'address' => $extraData['order_data']['address'] ?? null,
            'total_amount' => $input['amount'],
            'is_paid' => true,
            'payment_info' => json_encode([
                'transId' => $input['transId'],
                'payType' => $input['payType'] ?? null,
                'responseTime' => $input['responseTime']
            ])
        ];

        // Validate order data
        foreach (['user_id', 'total_amount', 'code'] as $field) {
            if (empty($orderData[$field])) {
                throw new \Exception("Thiếu trường bắt buộc: $field");
            }
        }

        $order = Order::create($orderData);

        // 7. Thêm sản phẩm vào đơn hàng và trừ kho
        if (!isset($extraData['order_data']['items']) || !is_array($extraData['order_data']['items'])) {
            throw new \Exception('Thiếu thông tin sản phẩm');
        }

        foreach ($extraData['order_data']['items'] as $item) {
            if (empty($item['product_id']) || empty($item['quantity'])) {
                continue; // Bỏ qua nếu thiếu thông tin cơ bản
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'] ?? 0
            ]);

            // Trừ số lượng tồn kho
            if (!empty($item['variant_id'])) {
                ProductVariant::where('id', $item['variant_id'])
                    ->decrement('quantity', $item['quantity']);
            } else {
                Product::where('id', $item['product_id'])
                    ->decrement('quantity', $item['quantity']);
            }
        }

        // 8. Xóa giỏ hàng
        if (isset($extraData['cart_items']) && is_array($extraData['cart_items'])) {
            CartItem::where('user_id', $extraData['user_id'])
                ->whereIn('id', $extraData['cart_items'])
                ->delete();
        }

        // 9. Cập nhật trạng thái đơn hàng
        OrderOrderStatus::create([
            'order_id' => $order->id,
            'order_status_id' => 2, // Đã thanh toán
            'modified_by' => null, // Hệ thống tự động
            'notes' => 'Thanh toán qua MoMo thành công'
        ]);

        DB::commit();

        Log::channel('momo')->info('Order processed successfully', [
            'orderId' => $order->code,
            'amount' => $order->total_amount
        ]);

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::channel('momo')->error('MoMo IPN processing failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'input' => $request->all()
        ]);
        return response()->json([
            'error' => $e->getMessage(),
            'success' => false
        ], 500);
    }
}

protected function computeMomoSignature($input)
{
    $rawHash = "accessKey=" . $this->momoConfig['accessKey'] .
        "&amount=" . $input['amount'] .
        "&extraData=" . $input['extraData'] .
        "&message=" . $input['message'] .
        "&orderId=" . $input['orderId'] .
        "&orderInfo=" . $input['orderInfo'] .
        "&orderType=" . $input['orderType'] .
        "&requestId=" . $input['requestId'] .
        "&responseTime=" . $input['responseTime'] .
        "&resultCode=" . $input['resultCode'] .
        "&transId=" . $input['transId'];

    return hash_hmac("sha256", $rawHash, $this->momoConfig['secretKey']);
}

        protected function verifyMomoSignature($input)
{
    $rawHash = "accessKey=".$this->momoConfig['accessKey'].
        "&amount=".$input['amount'].
        "&extraData=".$input['extraData'].
        "&message=".$input['message'].
        "&orderId=".$input['orderId'].
        "&orderInfo=".$input['orderInfo'].
        "&orderType=".$input['orderType'].
        "&requestId=".$input['requestId'].
        "&responseTime=".$input['responseTime'].
        "&resultCode=".$input['resultCode'].
        "&transId=".$input['transId'];

    $computedSignature = hash_hmac('sha256', $rawHash, $this->momoConfig['secretKey']);

    Log::channel('momo')->info('Signature Verification', [
        'computed' => $computedSignature,
        'received' => $input['signature']
    ]);

    return $computedSignature === $input['signature'];
}





        // Xử lý thanh toán VNPay
    protected function processVNPayPayment(Request $request)
    {
        $userId = auth()->id();
        $selectedItems = $request->input('selected_items', []);

        // Validate selected items
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }

        // Get cart items
        $cartItems = CartItem::where('user_id', $userId)
                    ->whereIn('id', $selectedItems)
                    ->with('product')
                    ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sản phẩm bạn chọn không tồn tại trong giỏ hàng.');
        }

        DB::beginTransaction();
        try {
            // Tạo đơn hàng (false = chưa trừ stock)
            $order = $this->createOrder($request, 4, false); // 4 là payment_id cho VNPay

            // Chuẩn bị dữ liệu thanh toán
            $paymentData = $this->prepareVNPayPaymentData($order);

            // Debug log
            Log::info('VNPay Payment Data:', $paymentData);

            // Xây dựng URL thanh toán
            $vnp_Url = $this->buildVNPayUrl($paymentData);

            // Xóa sản phẩm đã thanh toán khỏi giỏ hàng
            CartItem::where('user_id', $userId)
                    ->whereIn('id', $selectedItems)
                    ->delete();

            DB::commit();

            // Chuyển hướng đến VNPay
            return redirect()->away($vnp_Url);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay Payment Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Không thể khởi tạo thanh toán VNPay: '.$e->getMessage());
        }
    }

        protected function prepareVNPayPaymentData($order)
        {
            return [
                'vnp_Version' => '2.1.0',
                'vnp_TmnCode' => $this->vnpayConfig['vnp_TmnCode'],
                'vnp_Amount' => $order->total_amount * 100, // VNPay yêu cầu số tiền nhân 100
                'vnp_Command' => 'pay',
                'vnp_CreateDate' => date('YmdHis'),
                'vnp_CurrCode' => 'VND',
                'vnp_IpAddr' => request()->ip(),
                'vnp_Locale' => 'vn',
                'vnp_OrderInfo' => 'Thanh toán đơn hàng #' . $order->code,
                'vnp_OrderType' => 'billpayment',
                'vnp_ReturnUrl' => $this->vnpayConfig['vnp_Returnurl'],
                'vnp_TxnRef' => $order->code,
            ];
        }

        protected function buildVNPayUrl($inputData)
        {
            ksort($inputData);
            $hashdata = "";
            $i = 0;
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnpayConfig['vnp_HashSecret']);
            $inputData['vnp_SecureHash'] = $vnpSecureHash;

            return $this->vnpayConfig['vnp_Url'] . '?' . http_build_query($inputData);
        }

        public function vnpayReturn(Request $request)
        {
            try {
                $inputData = $request->all();
                $vnp_SecureHash = $inputData['vnp_SecureHash'];

                // Loại bỏ tham số hash khỏi dữ liệu
                unset($inputData['vnp_SecureHash']);

                ksort($inputData);
                $hashData = '';
                $i = 0;
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashData .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                }

                $secureHash = hash_hmac('sha512', $hashData, $this->vnpayConfig['vnp_HashSecret']);

                if ($secureHash != $vnp_SecureHash) {
                    return redirect()->route('client.home')->with('error', 'Chữ ký không hợp lệ');
                }

                $order = Order::where('code', $inputData['vnp_TxnRef'])->firstOrFail();

                if ($inputData['vnp_ResponseCode'] == '00') {
                    DB::transaction(function () use ($order) {
                        if (!$order->is_paid) {
                            $order->update([
                                'is_paid' => true,
                                'payment_id' => 4, // VNPay
                            ]);
                            $this->reduceStock($order);
                        }
                        $this->clearCart($order->user_id);
                    });

                    return redirect()->route('client.orders.show', $order->code)
                        ->with('success', 'Thanh toán VNPay thành công!');
                }

                return redirect()->route('client.orders.show', $order->code)
                    ->with('error', 'Thanh toán VNPay thất bại: ' . ($inputData['vnp_ResponseMessage'] ?? ''));
            } catch (\Exception $e) {
                Log::error('VNPay return error', ['error' => $e]);
                return redirect()->route('client.home')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
            }
        }

        protected function processSuccessfulPayment($input)
        {
            $extraData = json_decode($input['extraData'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid extraData format: '.json_last_error_msg());
            }
            $orderId = $extraData['order_id'] ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && !$order->is_paid) {
                    DB::transaction(function () use ($order) {
                        $order->update([
                            'is_paid' => true,
                            'payment_id' => 3, // Momo
                        ]);
                        $this->clearCart($order->user_id);
                        $this->reduceStock($order);
                    });
                }
            }
        }

        protected function processRegularOrder(Request $request)
        {
            DB::beginTransaction();
            try {
                $order = $this->createOrder($request, $request->paymentMethod, true);
                $this->clearCart(auth()->id() ?? 2);

                DB::commit();
                return redirect()->route('client.orders.show', $order->code)
                    ->with('success', 'Đặt hàng thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Place order error', ['error' => $e]);
                return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            }
        }

        protected function createOrder(Request $request, $paymentMethod, $reduceStock = false)
        {
            $userId = auth()->id() ?? 2;
            $cartItems = CartItem::where('user_id', $userId)->with('product')->get();

            $fullname = $request->field1 . ' ' . $request->field2;
            $total = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);

            // Xử lý coupon
            $couponData = $this->processCoupon($request->coupon_code, $total);

            $order = Order::create([
                'code' => 'DH' . strtoupper(Str::random(8)),
                'user_id' => $userId,
                'payment_id' => $paymentMethod,
                'phone_number' => $request->field5,
                'email' => $request->field4,
                'fullname' => $fullname,
                'address' => $request->field7,
                'note' => $request->field14,
                'total_amount' => max(($total - $couponData['discount']) + 30000, 0),
                'is_paid' => $paymentMethod == 2 ? false : true, // COD chưa thanh toán
                'coupon_id' => $couponData['coupon']?->id,
                'coupon_code' => $couponData['coupon']?->code,
                'coupon_discount_type' => $couponData['coupon']?->discount_type,
                'coupon_discount_value' => $couponData['coupon']?->discount_value,
                'max_discount_value' => $couponData['max_discount'],
            ]);
            OrderOrderStatus::create([
                'order_id' => $order->id,
                'order_status_id' => 1, // 1 = Chờ xác nhận
                'modified_by' => auth()->id() ?? null,
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

        protected function processCoupon($couponCode, $total)
        {
            if (!$couponCode) return ['discount' => 0, 'coupon' => null, 'max_discount' => null];

            $coupon = Coupon::where('code', $couponCode)
                ->where('is_active', 1)
                ->where(function ($q) {
                    $q->where('is_expired', 0)->orWhere('end_date', '>=', now());
                })->first();

            if (!$coupon) return ['discount' => 0, 'coupon' => null, 'max_discount' => null];

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
                'coupon' => $coupon,
                'max_discount' => $restriction->max_discount_value ?? null
            ];
        }

        protected function reduceStock($order)
        {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    ProductVariant::where('id', $item->product_variant_id)
                        ->decrement('quantity', $item->quantity);
                } else {
                    Product::where('id', $item->product_id)
                        ->decrement('quantity', $item->quantity);
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
            \Log::info('User ID: ' . $userId);
            $orders = Order::where('user_id', $userId)
                ->with(['currentStatus.orderStatus'])
                ->orderByDesc('created_at')
                ->paginate(10);

            return view('client.orders.purchase_history', compact('orders'));
        }
    }
