<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin\CartItem;
use App\Models\Admin\OrderOrderStatus;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
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
        $userId        = auth()->id();
        $user          = auth()->user();
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
            'cartItems'      => $cartItems,
            'total'          => $total,
            'user'           => $user,
            'defaultAddress' => $defaultAddress,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->status === 'locked') {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa, không thể đặt hàng!');
        }

        $request->validate([
            'field1'        => 'required|string|max:255',
            'field2'        => 'required|string|max:255',
            'field4'        => 'required|email|max:255',
            'field5'        => 'required|string|max:20',
            'field7'        => 'required|string|max:255',
            'paymentMethod' => 'required|in:1,2,3,4', // 1: Bank, 2: COD, 3: MoMo, 4: VNPay
            'selected_items' => 'required|array',
        ]);

        if ($request->paymentMethod == 3) {
            return $this->processMomoPayment($request);
        } elseif ($request->paymentMethod == 4) {
            return $this->processVNPayPayment($request);
        }

        return $this->processRegularOrder($request);
    }

    protected function processMomoPayment(Request $request)
{
    $userId = auth()->id();
    $selectedItems = $request->input('selected_items', []);

    if (empty($selectedItems)) {
        return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
    }

    $cartItems = CartItem::where('user_id', $userId)
        ->whereIn('id', $selectedItems)
        ->with('product')
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Giỏ hàng trống');
    }

    // Tính toán tổng tiền
    $total = $cartItems->sum(function ($item) {
        return ($item->product->price ?? 0) * $item->quantity;
    });
    $shippingFee = 30000;
    $totalAmount = $total + $shippingFee;

    try {
        $orderId = time() . ""; // Sử dụng timestamp làm orderId

        DB::beginTransaction();

        // Tạo đơn hàng
        $order = Order::create([
            'code' => $orderId,
            'user_id' => $userId,
            'payment_id' => 3, // MoMo
            'phone_number' => $request->field5,
            'email' => $request->field4,
            'fullname' => $request->field1 . ' ' . $request->field2,
            'address' => $request->field7,
            'total_amount' => $totalAmount,
            'is_paid' => false,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price ?? 0,
            ]);
        }

        // Chuẩn bị dữ liệu thanh toán
        $requestId = time() . "";
        $orderInfo = "Thanh toán đơn hàng #" . $orderId;
        $extraData = json_encode([
            'order_id' => $order->id,
            'user_id' => $userId,
            'cart_items' => $selectedItems
        ]);
        $redirectUrl = route('checkout.momo.return');
        $ipnUrl = route('checkout.momo.ipn');

        // Tạo chữ ký theo đúng định dạng MoMo yêu cầu
        $rawHash = "accessKey=" . $this->momoConfig['accessKey'] . 
                  "&amount=" . $totalAmount . 
                  "&extraData=" . $extraData . 
                  "&ipnUrl=" . $ipnUrl . 
                  "&orderId=" . $orderId . 
                  "&orderInfo=" . $orderInfo . 
                  "&partnerCode=" . $this->momoConfig['partnerCode'] . 
                  "&redirectUrl=" . $redirectUrl . 
                  "&requestId=" . $requestId . 
                  "&requestType=" . $this->momoConfig['requestType'];

        $signature = hash_hmac("sha256", $rawHash, $this->momoConfig['secretKey']);

        // Tạo data gửi đi
        $data = [
            'partnerCode' => $this->momoConfig['partnerCode'],
            'partnerName' => "Test Merchant",
            'storeId' => "store001",
            'requestId' => $requestId,
            'amount' => $totalAmount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $this->momoConfig['requestType'],
            'signature' => $signature
        ];

        Log::info('MoMo Request Data:', $data);

        // Gửi request đến MoMo
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->momoConfig['endpoint'], $data);

        $result = $response->json();

        if (!isset($result['payUrl'])) {
            Log::error('MoMo Payment Error Response:', $result);
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

   public function momoReturn(Request $request)
{
    Log::channel('momo')->info('MoMo Return Data:', $request->all());

    try {
        // 1. Kiểm tra kết quả thanh toán từ MoMo
        if ($request->resultCode != 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Thanh toán thất bại: ' . ($request->message ?? ''));
        }

        // 2. Xác thực chữ ký
        if (!$this->verifyMomoSignature($request->all())) {
            throw new \Exception('Chữ ký không hợp lệ');
        }

        // 3. Lấy thông tin đơn hàng từ extraData
        $extraData = json_decode($request->extraData, true);
        if (!isset($extraData['order_id'])) {
            throw new \Exception('Không tìm thấy thông tin đơn hàng');
        }

        // 4. Tìm và cập nhật đơn hàng
        $order = Order::find($extraData['order_id']);
        if (!$order) {
            throw new \Exception('Đơn hàng không tồn tại');
        }

        DB::beginTransaction();

        // 5. Nếu chưa thanh toán, cập nhật is_paid = 1
        if (!$order->is_paid) {
            $order->update([
                'is_paid' => 1,
                // Không cập nhật payment_info nữa
                'updated_at' => now()
            ]);

            // Thay thế tất cả các đoạn code giảm số lượng tồn kho bằng:
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::where('id', $item->product_variant_id)
                            ->decrement('stock', $item->quantity);
                    } else {
                        Product::where('id', $item->product_id)
                            ->decrement('stock', $item->quantity);
                             ProductVariant::where('id', $item->product_variant_id)
                            ->decrement('stock', $item->quantity);
                    }
                }

            // Xóa giỏ hàng nếu có thông tin cart_items
            if (isset($extraData['cart_items']) && !empty($extraData['cart_items'])) {
                CartItem::where('user_id', $extraData['user_id'])
                    ->whereIn('id', $extraData['cart_items'])
                    ->delete();
            }

            // Cập nhật trạng thái đơn hàng
            OrderOrderStatus::create([
                'order_id' => $order->id,
                'order_status_id' => 9, // Đã thanh toán
                 'modified_by' => $order->user_id ?? 5,
                'notes' => 'Thanh toán qua MoMo thành công',
            ]);
        }

        DB::commit();

        // 6. Chuyển hướng về trang thông báo thành công
        return redirect()->route('client.orders.show', $order->code)
            ->with('success', 'Thanh toán thành công!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('MoMo Return Error: ' . $e->getMessage());
        return redirect()->route('cart.index')
            ->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
    }
}

    public function momoIPN(Request $request)
{
    Log::channel('momo')->info('IPN Received', $request->all());

    DB::beginTransaction();
    try {
        // 1. Kiểm tra các trường bắt buộc
        $requiredFields = ['resultCode', 'orderId', 'amount', 'extraData', 'signature'];
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                throw new \Exception("Thiếu trường bắt buộc: $field");
            }
        }

        // 2. Xác thực chữ ký
        if (!$this->verifyMomoSignature($request->all())) {
            Log::channel('momo')->error('Invalid MoMo signature', [
                'input' => $request->all(),
                'computed_signature' => $this->computeMomoSignature($request->all()),
            ]);
            throw new \Exception('Chữ ký không hợp lệ');
        }

        // 3. Chỉ xử lý khi thanh toán thành công (resultCode = 0)
        if ($request->resultCode != 0) {
            Log::channel('momo')->warning('MoMo payment failed', [
                'orderId' => $request->orderId,
                'message' => $request->message ?? null,
            ]);
            return response()->json(['success' => false, 'message' => 'Payment failed']);
        }

        // 4. Giải mã extraData
        $extraData = json_decode($request->extraData, true);
        if (!$extraData || !isset($extraData['order_id'])) {
            throw new \Exception('Dữ liệu extraData không hợp lệ');
        }

        // 5. Lấy thông tin đơn hàng
        $order = Order::findOrFail($extraData['order_id']);
        
        // 6. Kiểm tra tránh xử lý trùng lặp
        if ($order->is_paid) {
            Log::channel('momo')->info('Order already processed', [
                'orderId' => $request->orderId,
                'orderStatus' => $order->is_paid,
            ]);
            return response()->json(['success' => true, 'message' => 'Order already processed']);
        }

        // 7. Cập nhật đơn hàng - Đặt is_paid = 1 (không cập nhật payment_info)
        $order->update([
            'is_paid' => true,
            'updated_at' => now()
        ]);

        // 8. Trừ số lượng tồn kho
        // Thay thế tất cả các đoạn code giảm số lượng tồn kho bằng:
            foreach ($order->items as $item) {
    if ($item->product_variant_id) {
        // Giảm stock của biến thể sản phẩm
        $variant = ProductVariant::where('id', $item->product_variant_id)->first();
        if ($variant) {
            $variant->decrement('stock', $item->quantity);
            
            // Giảm stock của sản phẩm chính tương ứng
            Product::where('id', $variant->product_id)
                ->decrement('stock', $item->quantity);
        }
    } else {
        // Giảm stock của sản phẩm đơn (không có biến thể)
        Product::where('id', $item->product_id)
            ->decrement('stock', $item->quantity);
    }
}

        // 9. Xóa giỏ hàng nếu có thông tin cart_items
        if (isset($extraData['cart_items']) && !empty($extraData['cart_items'])) {
            $cartItemsToDelete = is_array($extraData['cart_items']) 
                ? $extraData['cart_items'] 
                : json_decode($extraData['cart_items'], true);

            CartItem::where('user_id', $extraData['user_id'])
                ->whereIn('id', $cartItemsToDelete)
                ->delete();
        }

        // 10. Cập nhật trạng thái đơn hàng
        OrderOrderStatus::create([
            'order_id' => $order->id,
            'order_status_id' => 9, // Đã thanh toán
             'modified_by' => $order->user_id ?? 5, // Hệ thống tự động
            'notes' => 'Thanh toán qua MoMo thành công',
        ]);

        DB::commit();

        Log::channel('momo')->info('Order processed successfully', [
            'orderId' => $order->code,
            'amount' => $order->total_amount,
            'items_count' => $order->items->count(),
        ]);

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::channel('momo')->error('MoMo IPN processing failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'input' => $request->all(),
        ]);
        return response()->json([
            'error' => $e->getMessage(),
            'success' => false,
        ], 500);
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

    // Kiểm tra các trường bắt buộc
    foreach ($requiredFields as $field) {
        if (!isset($params[$field])) {
            Log::channel('momo')->error("Missing required field: {$field}");
            return false;
        }
    }

    // Tạo chuỗi raw hash theo đúng thứ tự MoMo yêu cầu
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

    Log::info('Raw hash for signature verification: ' . $rawHash);

    // Tính toán chữ ký
    $computedSignature = hash_hmac('sha256', $rawHash, $this->momoConfig['secretKey']);

    Log::info('Signature verification', [
        'computed' => $computedSignature,
        'received' => $params['signature']
    ]);

    return hash_equals($computedSignature, $params['signature']);
}

    protected function computeMomoSignature($input)
    {
        $rawHash = "accessKey=" . $this->momoConfig['accessKey'] .
            "&amount=" . $input['amount'] .
            "&extraData=" . $input['extraData'] .
            "&message=" . ($input['message'] ?? '') .
            "&orderId=" . $input['orderId'] .
            "&orderInfo=" . ($input['orderInfo'] ?? '') .
            "&orderType=" . ($input['orderType'] ?? '') .
            "&requestId=" . ($input['requestId'] ?? '') .
            "&responseTime=" . $input['responseTime'] .
            "&resultCode=" . $input['resultCode'] .
            "&transId=" . $input['transId'];

        return hash_hmac('sha256', $rawHash, $this->momoConfig['secretKey']);
    }

    protected function processVNPayPayment(Request $request)
{
    $userId = auth()->id();
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

    DB::beginTransaction();
    try {
        // Tạo đơn hàng với is_paid = false ban đầu
        $order = $this->createOrder($request, 4, false); // 4 là payment_id cho VNPay, false = không trừ stock ngay

        // Chuẩn bị dữ liệu thanh toán
        $paymentData = $this->prepareVNPayPaymentData($order);

        // Thêm thông tin giỏ hàng vào extraData (giống MoMo)
        $paymentData['vnp_OrderInfo'] = json_encode([
            'order_id' => $order->id,
            'user_id' => $userId,
            'cart_items' => $selectedItems
        ]);

        // Xây dựng URL thanh toán
        $vnp_Url = $this->buildVNPayUrl($paymentData);

        DB::commit();

        // Chuyển hướng đến VNPay
        return redirect()->away($vnp_Url);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('VNPay Payment Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        return back()->with('error', 'Không thể khởi tạo thanh toán VNPay: ' . $e->getMessage());
    }
}

    protected function prepareVNPayPaymentData($order)
    {
        return [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => $this->vnpayConfig['vnp_TmnCode'],
            'vnp_Amount'     => $order->total_amount * 100, // VNPay yêu cầu số tiền nhân 100
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => request()->ip(),
            'vnp_Locale'     => 'vn',
            'vnp_OrderInfo'  => 'Thanh toán đơn hàng #' . $order->code,
            'vnp_OrderType'  => 'billpayment',
            'vnp_ReturnUrl'  => $this->vnpayConfig['vnp_Returnurl'],
            'vnp_TxnRef'     => $order->code,
        ];
    }

    protected function buildVNPayUrl($inputData)
    {
        ksort($inputData);
        $hashdata = "";
        $i        = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnpSecureHash               = hash_hmac('sha512', $hashdata, $this->vnpayConfig['vnp_HashSecret']);
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
            DB::beginTransaction();
            try {
                // Chỉ xử lý nếu chưa thanh toán
                if (!$order->is_paid) {
                    // Cập nhật trạng thái thanh toán
                    $order->update([
                        'is_paid' => true,
                        'payment_id' => 4, // VNPay
                    ]);

                    // Trừ số lượng tồn kho cho cả sản phẩm và biến thể
                    DB::transaction(function () use ($order) {
                        foreach ($order->items as $item) {
                            if ($item->product_variant_id) {
                                $variant = ProductVariant::where('id', $item->product_variant_id)->first();
                                if ($variant) {
                                    $variant->decrement('stock', $item->quantity);
                                    Product::where('id', $variant->product_id)
                                        ->decrement('stock', $item->quantity);
                                }
                            } else {
                                Product::where('id', $item->product_id)
                                    ->decrement('stock', $item->quantity);
                            }
                        }
                    });

                    // Xóa giỏ hàng nếu có thông tin cart_items
                    $orderInfo = json_decode($inputData['vnp_OrderInfo'], true);
                    if (isset($orderInfo['cart_items'])) {
                        CartItem::where('user_id', $order->user_id)
                            ->whereIn('id', $orderInfo['cart_items'])
                            ->delete();
                    }

                    // Cập nhật trạng thái đơn hàng
                    OrderOrderStatus::create([
                        'order_id' => $order->id,
                        'order_status_id' => 9, // Đã thanh toán
                        'modified_by' => $order->user_id ?? 5,
                        'notes' => 'Thanh toán qua VNPay thành công',
                    ]);
                }

                DB::commit();

                return redirect()->route('client.orders.show', $order->code)
                    ->with('success', 'Thanh toán VNPay thành công!');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('VNPay Return Error: ' . $e->getMessage());
                return redirect()->route('client.orders.show', $order->code)
                    ->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
            }
        }

        // Nếu thanh toán không thành công
        return redirect()->route('client.orders.show', $order->code)
            ->with('error', 'Thanh toán VNPay thất bại: ' . ($inputData['vnp_ResponseMessage'] ?? ''));

    } catch (\Exception $e) {
        Log::error('VNPay return error', ['error' => $e]);
        return redirect()->route('client.home')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
    }
}

    protected function processRegularOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = $this->createOrder($request, $request->paymentMethod, true);
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

   protected function createOrder(Request $request, $paymentMethod, $reduceStock = false)
{
    $userId = auth()->id();
    $selectedItems = $request->input('selected_items', []);
    
    $cartItems = CartItem::where('user_id', $userId)
        ->whereIn('id', $selectedItems)
        ->with('product')
        ->get();

    $fullname = $request->field1 . ' ' . $request->field2;
    $total = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);

    // Xử lý coupon
    $couponData = $this->processCoupon($request->coupon_code, $total);

    // Sửa lại logic is_paid: chỉ đặt true nếu là COD (paymentMethod = 2)
    $isPaid = $paymentMethod == 2 ? false : false; // Tất cả các phương thức khác ban đầu đều false

    $order = Order::create([
        'code'                  => 'DH' . strtoupper(Str::random(8)),
        'user_id'               => $userId,
        'payment_id'            => $paymentMethod,
        'phone_number'          => $request->field5,
        'email'                 => $request->field4,
        'fullname'              => $fullname,
        'address'               => $request->field7,
        'note'                  => $request->field14,
        'total_amount'          => max(($total - $couponData['discount']) + 30000, 0),
        'is_paid'               => $isPaid, // Sửa lại theo logic mới
        'coupon_id'             => $couponData['coupon']?->id,
        'coupon_code'           => $couponData['coupon']?->code,
        'coupon_discount_type'  => $couponData['coupon']?->discount_type,
        'coupon_discount_value' => $couponData['coupon']?->discount_value,
        'max_discount_value'    => $couponData['max_discount'],
    ]);

    OrderOrderStatus::create([
        'order_id'        => $order->id,
        'order_status_id' => 1, // 1 = Chờ xác nhận
        'modified_by'     => $order->user_id ?? 5,
    ]);

    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id'           => $order->id,
            'product_id'         => $item->product_id,
            'product_variant_id' => $item->product_variant_id,
            'name'               => $item->product->name ?? null,
            'price'              => $item->product->price ?? 0,
            'quantity'           => $item->quantity ?? 1,
        ]);
    }

    if ($reduceStock) {
        $this->reduceStock($order);
    }

    return $order;
}

    protected function processCoupon($couponCode, $total)
    {
        if (!$couponCode) {
            return ['discount' => 0, 'coupon' => null, 'max_discount' => null];
        }

        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->where('is_expired', 0)->orWhere('end_date', '>=', now());
            })->first();

        if (!$coupon) {
            return ['discount' => 0, 'coupon' => null, 'max_discount' => null];
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
            'discount'     => $discount,
            'coupon'       => $coupon,
            'max_discount' => $restriction->max_discount_value ?? null,
        ];
    }

            protected function reduceStock($order)
        {
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    ProductVariant::where('id', $item->product_variant_id)
                        ->decrement('stock', $item->quantity); // Đổi quantity -> stock
                } else {
                    Product::where('id', $item->product_id)
                        ->decrement('stock', $item->quantity); // Đổi quantity -> stock
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