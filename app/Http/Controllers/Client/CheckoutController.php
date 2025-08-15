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
use App\Models\Admin\Review;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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
        $userAddresses = Auth::user()->addresses;

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

        // Thêm đoạn code đọc file JSON địa chỉ
        $vnLocationsPath = public_path('assets/Client/js/vn-location.json');
        $vnLocationsData = [];
        if (File::exists($vnLocationsPath)) {
            $vnLocationsData = json_decode(File::get($vnLocationsPath), true);
        }

        return view('client.checkout.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => auth()->user(),
            'addresses' => UserAddress::where('user_id', $userId)->orderBy('id_default', 'DESC')->get(),
            'defaultAddress' => UserAddress::where('user_id', $userId)->where('id_default', 1)->first(),
            'coupons' => $coupons,
            'userAddresses' => $userAddresses,
            // Truyền biến vnLocationsData vào view
            'vnLocationsData' => $vnLocationsData,
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

            // Store order data in session với timestamp để tránh expired
            $orderData = $this->prepareOrderData($request, $couponData);
            $orderData['created_at'] = now()->timestamp;
            Session::put('pending_order', $orderData);

            if ($request->paymentMethod == 3) {
                return $this->processMomoPayment($request, $couponData);
            } elseif ($request->paymentMethod == 4) {
                return $this->processVNPayPayment($request, $couponData);
            }

            return $this->processRegularOrder($request, $couponData);
        } catch (\Exception $e) {
            Log::error('CheckoutController@placeOrder - Error: ' . $e->getMessage());
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
            'is_paid' => false,
            'coupon_id' => $couponData['coupon']?->id,
            'coupon_code' => $couponData['coupon']?->code,
            'coupon_discount' => $couponData['discount'],
            'coupon_discount_type' => $couponData['coupon']?->discount_type,
            'coupon_discount_value' => $couponData['coupon']?->discount_value,
            'cart_items' => $cartItems->map(function ($item) {
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

            // Lưu thông tin vào session với key unique
            Session::put('momo_order_code', $orderCode);
            Session::put('momo_request_id', $requestId);

            $extraData = json_encode([
                'order_code' => $orderCode,
                'user_id' => auth()->id(),
                'request_id' => $requestId,
                'timestamp' => now()->timestamp
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

            Log::info('MoMo Payment Request', [
                'order_code' => $orderCode,
                'amount' => $totalAmount,
                'request_id' => $requestId
            ]);

            $response = Http::timeout(30)->withHeaders(['Content-Type' => 'application/json'])
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

            Log::info('MoMo Payment Response', $result);

            if (!isset($result['payUrl'])) {
                throw new \Exception($result['message'] ?? 'Không thể khởi tạo thanh toán MoMo');
            }

            return redirect()->away($result['payUrl']);
        } catch (\Exception $e) {
            Log::error('MoMo Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Lỗi thanh toán MoMo: ' . $e->getMessage());
        }
    }

    protected function processVNPayPayment(Request $request, $couponData)
    {
        try {
            $orderData = $this->prepareOrderData($request, $couponData);
            $orderCode = 'DH' . strtoupper(Str::random(8));

            // Thêm timestamp để kiểm soát thời gian sống
            $orderData['created_at'] = now()->timestamp;

            // Lưu vào session với key duy nhất cho VNPay
            session([
                'vnpay_order_data' => $orderData,
                'vnpay_order_code' => $orderCode,
                'vnpay_session_id' => session()->getId() // Lưu lại session ID để debug
            ]);

            // Commit session ngay lập tức
            session()->save();

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
                    'timestamp' => now()->timestamp,
                    'session_id' => session()->getId() // Thêm session ID vào thông tin đơn hàng
                ]),
                'vnp_OrderType' => 'billpayment',
                'vnp_ReturnUrl' => $this->vnpayConfig['vnp_Returnurl'],
                'vnp_TxnRef' => $orderCode,
            ];

            ksort($paymentData);
            $hashData = http_build_query($paymentData);
            $vnpSecureHash = hash_hmac('sha512', $hashData, $this->vnpayConfig['vnp_HashSecret']);
            $paymentData['vnp_SecureHash'] = $vnpSecureHash;

            Log::info('VNPay Payment Request', [
                'order_code' => $orderCode,
                'amount' => $orderData['total_amount'],
                'session_id' => session()->getId(),
                'session_data' => session()->all() // Log toàn bộ session để debug
            ]);

            return redirect()->away($this->vnpayConfig['vnp_Url'] . '?' . http_build_query($paymentData));
        } catch (\Exception $e) {
            Log::error('VNPay Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Không thể khởi tạo thanh toán VNPay: ' . $e->getMessage());
        }
    }

    protected function processRegularOrder(Request $request, $couponData)
    {
        DB::beginTransaction();
        try {
            $orderData = Session::get('pending_order');
            $order = $this->saveOrderToDatabase($orderData);
            $this->clearCartItems($orderData['selected_items']);

            DB::commit();
            Session::forget('pending_order');

            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Đặt hàng thành công! Vui lòng chờ xác nhận từ cửa hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Regular Order Error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // ==================== CALLBACK HANDLERS ====================

    /**
     * MoMo IPN (Instant Payment Notification) - Server to Server
     * Đây là callback từ MoMo server gọi đến server của bạn
     */
    public function momoIPN(Request $request)
    {
        Log::info('MoMo IPN Received', $request->all());

        try {
            // Verify signature trước khi xử lý
            if (!$this->verifyMomoSignature($request->all())) {
                Log::error('MoMo IPN - Invalid signature');
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }

            $resultCode = $request->input('resultCode');
            $extraData = json_decode($request->input('extraData', '{}'), true);
            $orderCode = $extraData['order_code'] ?? null;

            if (!$orderCode) {
                Log::error('MoMo IPN - Missing order code');
                return response()->json(['RspCode' => '99', 'Message' => 'Missing order data']);
            }

            // Kiểm tra đơn hàng đã tồn tại chưa
            $existingOrder = Order::where('code', $orderCode)->first();
            if ($existingOrder) {
                Log::info('MoMo IPN - Order already exists', ['order_code' => $orderCode]);
                return response()->json(['RspCode' => '00', 'Message' => 'Order already processed']);
            }

            if ($resultCode == 0) {
                // Thanh toán thành công
                $this->processMomoSuccess($request->all(), $orderCode);
                return response()->json(['RspCode' => '00', 'Message' => 'Success']);
            } else {
                // Thanh toán thất bại
                Log::warning('MoMo IPN - Payment failed', [
                    'order_code' => $orderCode,
                    'result_code' => $resultCode,
                    'message' => $request->input('message')
                ]);
                return response()->json(['RspCode' => '00', 'Message' => 'Payment failed but acknowledged']);
            }
        } catch (\Exception $e) {
            Log::error('MoMo IPN Error: ' . $e->getMessage());
            return response()->json(['RspCode' => '99', 'Message' => 'System error']);
        }
    }

    /**
     * MoMo Return URL - User redirect back
     * Đây là khi user được redirect về từ MoMo
     */
    public function momoReturn(Request $request)
    {
        Log::info('MoMo Return', $request->all());

        try {
            $resultCode = $request->input('resultCode');
            $extraData = json_decode($request->input('extraData', '{}'), true);
            $orderCode = $extraData['order_code'] ?? null;

            if ($resultCode != 0) {
                Log::warning('MoMo Return - Payment failed', [
                    'result_code' => $resultCode,
                    'message' => $request->input('message')
                ]);
                return redirect()->route('cart.index')
                    ->with('error', 'Thanh toán thất bại: ' . ($request->input('message') ?? 'Lỗi không xác định'));
            }

            if (!$this->verifyMomoSignature($request->all())) {
                Log::error('MoMo Return - Invalid signature');
                return redirect()->route('cart.index')
                    ->with('error', 'Chữ ký không hợp lệ');
            }

            // Kiểm tra đơn hàng đã được tạo chưa (từ IPN)
            $order = Order::where('code', $orderCode)->first();
            if ($order) {
                // Kiểm tra xem trạng thái đã tồn tại chưa trước khi tạo mới
                $existingStatus = OrderOrderStatus::where('order_id', $order->id)
                    ->where('order_status_id', 1)
                    ->where('modified_by', $order->user_id ?? 5)
                    ->first();

                if (!$existingStatus) {
                    OrderOrderStatus::create([
                        'order_id' => $order->id,
                        'order_status_id' => 1,
                        'modified_by' => $order->user_id ?? 5,
                        'notes' => 'Thanh toán qua MoMo thành công',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]);
                }


                Session::forget(['pending_order', 'momo_order_code', 'momo_request_id']);
                return redirect()->route('client.orders.show', $order->code)
                    ->with('success', 'Thanh toán thành công!');
            }


            // Nếu IPN chưa được gọi, xử lý tại đây
            DB::beginTransaction();
            $orderData = Session::get('pending_order');

            if (!$orderData) {
                throw new \Exception('Không tìm thấy thông tin đơn hàng');
            }

            $order = $this->saveOrderToDatabase($orderData);
            $order->update(['is_paid' => 1]);
            $this->reduceStock($order);
            $this->clearCartItems($orderData['selected_items']);

            // Kiểm tra trước khi tạo trạng thái đơn hàng
            $existingStatus = OrderOrderStatus::where('order_id', $order->id)
                ->where('order_status_id', 1)
                ->where('modified_by', $order->user_id ?? 5)
                ->first();

            if (!$existingStatus) {
                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => 1,
                    'modified_by' => $order->user_id ?? 5,
                    'notes' => 'Thanh toán qua MoMo thành công',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }

            DB::commit();
            Session::forget(['pending_order', 'momo_order_code', 'momo_request_id']);

            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MoMo Return Error: ' . $e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * VNPay Return URL - User redirect back
     */
    public function vnpayReturn(Request $request)
    {
        Log::info('VNPay Return - Full Request Data:', $request->all());
        Log::debug('Session data before:', session()->all());

        try {
            // Xác thực chữ ký
            $inputData = $request->all();
            $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
            unset($inputData['vnp_SecureHash']);

            ksort($inputData);
            $hashData = http_build_query($inputData);
            $secureHash = hash_hmac('sha512', $hashData, $this->vnpayConfig['vnp_HashSecret']);

            if (!hash_equals($secureHash, $vnp_SecureHash)) {
                Log::error('VNPay Return - Invalid signature');
                return redirect()->route('cart.index')
                    ->with('error', 'Chữ ký không hợp lệ');
            }

            $responseCode = $inputData['vnp_ResponseCode'] ?? '';
            $orderCode = $inputData['vnp_TxnRef'] ?? '';

            if ($responseCode !== '00') {
                Log::warning('VNPay Return - Payment failed', [
                    'response_code' => $responseCode,
                    'order_code' => $orderCode
                ]);
                Session::forget(['pending_order', 'vnpay_order_data', 'vnpay_order_code']);
                return redirect()->route('cart.index')
                    ->with('error', 'Thanh toán VNPay thất bại: ' . ($inputData['vnp_ResponseMessage'] ?? ''));
            }

            DB::beginTransaction();

            // Thử lấy dữ liệu từ nhiều nguồn khác nhau
            $orderData = Session::get('vnpay_order_data')
                ?? Session::get('pending_order')
                ?? $this->reconstructOrderFromVNPayData($inputData);

            if (!$orderData) {
                throw new \Exception('Không tìm thấy thông tin đơn hàng trong session');
            }

            // Kiểm tra thời gian tạo đơn hàng (không quá 1 giờ)
            if (now()->timestamp - ($orderData['created_at'] ?? 0) > 3600) {
                throw new \Exception('Thông tin đơn hàng đã hết hạn');
            }

            // Kiểm tra đơn hàng đã tồn tại chưa
            $order = Order::where('code', $orderCode)->first();

            if (!$order) {
                $order = $this->saveOrderToDatabase($orderData);
                $order->update([
                    'is_paid' => 1,
                    'payment_id' => 4,
                    'code' => $orderCode // Đảm bảo mã đơn hàng trùng với VNPay
                ]);
                $this->reduceStock($order);
                $this->clearCartItems($orderData['selected_items']);
            }

            // Kiểm tra trạng thái đã tồn tại chưa
            $existingStatus = OrderOrderStatus::where([
                'order_id' => $order->id,
                'order_status_id' => 1,
                'modified_by' => $order->user_id ?? 5
            ])->first();

            if (!$existingStatus) {
                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => 1,
                    'modified_by' => $order->user_id ?? 5,
                    'notes' => 'Thanh toán qua VNPay thành công',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            // Xóa session sau khi xử lý thành công
            Session::forget(['pending_order', 'vnpay_order_data', 'vnpay_order_code']);
            Session::save();

            return redirect()->route('client.orders.show', $order->code)
                ->with('success', 'Thanh toán VNPay thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay Return Error: ' . $e->getMessage());

            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage())
                ->with('transaction_no', $inputData['vnp_TransactionNo'] ?? '');
        }
    }

    protected function reconstructOrderFromVNPayData($vnpayData)
    {
        try {
            $orderInfo = json_decode($vnpayData['vnp_OrderInfo'] ?? '{}', true);
            if (empty($orderInfo)) {
                return null;
            }

            // Lấy thông tin cơ bản
            $userId = $orderInfo['user_id'] ?? null;
            $orderCode = $vnpayData['vnp_TxnRef'] ?? null;
            $amount = ($vnpayData['vnp_Amount'] ?? 0) / 100;

            if (!$userId || !$orderCode) {
                return null;
            }

            // Tạo lại dữ liệu đơn hàng cơ bản
            return [
                'user_id' => $userId,
                'payment_id' => 4, // VNPay
                'total_amount' => $amount,
                'is_paid' => true,
                'created_at' => $orderInfo['timestamp'] ?? now()->timestamp,
                // Các thông tin khác có thể thêm nếu cần
            ];
        } catch (\Exception $e) {
            Log::error('Failed to reconstruct order from VNPay data: ' . $e->getMessage());
            return null;
        }
    }

    // ==================== HELPER METHODS ====================

    protected function processMomoSuccess($momoData, $orderCode)



    {
        DB::beginTransaction();
        try {
            // Tìm session data từ cache hoặc database
            $orderData = $this->getOrderDataFromCache($orderCode) ?? Session::get('pending_order');

            if (!$orderData) {
                // Nếu không có session, tạo order từ thông tin MoMo
                throw new \Exception('Không tìm thấy thông tin đơn hàng');
            }


            $order = $this->saveOrderToDatabase($orderData);
            $order->update(['is_paid' => 1, 'code' => $orderCode]);
            $this->reduceStock($order);
            $this->clearCartItems($orderData['selected_items']);

            // Kiểm tra trước khi tạo trạng thái đơn hàng
            $existingStatus = OrderOrderStatus::where('order_id', $order->id)
                ->where('order_status_id', 9)
                ->where('modified_by', $order->user_id ?? 5)
                ->first();

            if (!$existingStatus) {
                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => 9,
                    'modified_by' => $order->user_id ?? 5,
                    'notes' => 'Thanh toán qua MoMo thành công (IPN)',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }

            DB::commit();
            Log::info('MoMo IPN - Order processed successfully', ['order_code' => $orderCode]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MoMo IPN Processing Error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function getOrderDataFromCache($orderCode)
    {
        // Implement cache mechanism nếu cần
        // Ví dụ: Cache::get('order_data_' . $orderCode)
        return null;
    }

    protected function saveOrderToDatabase($orderData)
    {
        DB::beginTransaction();
        try {
            $orderCode = 'DH' . strtoupper(Str::random(8));

            $order = Order::create([
                'code' => $orderCode,
                'user_id' => $orderData['user_id'],
                'payment_id' => $orderData['payment_id'],
                'phone_number' => $orderData['phone_number'],
                'email' => $orderData['email'],
                'fullname' => $orderData['fullname'],
                'address' => $orderData['address'],
                'note' => $orderData['note'] ?? '',
                'total_amount' => $orderData['total_amount'],
                'is_paid' => $orderData['is_paid'] ?? false,
                'coupon_id' => $orderData['coupon_id'],
                'coupon_code' => $orderData['coupon_code'],
                'coupon_discount' => $orderData['coupon_discount'],
                'coupon_discount_type' => $orderData['coupon_discount_type'],
                'coupon_discount_value' => $orderData['coupon_discount_value'],
            ]);

            // Kiểm tra trước khi tạo trạng thái đơn hàng
            $existingStatus = OrderOrderStatus::where([
                'order_id' => $order->id,
                'order_status_id' => 1,
                'modified_by' => $order->user_id ?? 5
            ])->first();

            if (!$existingStatus) {
                OrderOrderStatus::create([
                    'order_id' => $order->id,
                    'order_status_id' => 1,
                    'modified_by' => $order->user_id ?? 5,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }

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

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function verifyMomoSignature($params)
    {
        $requiredFields = [
            'partnerCode',
            'orderId',
            'requestId',
            'amount',
            'orderInfo',
            'orderType',
            'transId',
            'resultCode',
            'message',
            'payType',
            'responseTime',
            'extraData',
            'signature'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($params[$field])) {
                Log::warning('MoMo Signature - Missing field: ' . $field);
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
        $isValid = hash_equals($computedSignature, $params['signature']);

        if (!$isValid) {
            Log::warning('MoMo Signature Mismatch', [
                'computed' => $computedSignature,
                'received' => $params['signature']
            ]);
        }

        return $isValid;
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
            ->where(function ($q) {
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

    protected function clearCartItems($selectedItems)
    {
        CartItem::whereIn('id', $selectedItems)->delete();
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
        $coupons = $this->getAvailableCoupons();
        $orders = Order::where('user_id', $userId)
            ->with([
                'currentStatus.orderStatus',
                'items.product.reviews.multimedia',
                'items.variant'
            ])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Map xác định đã đánh giá hay chưa
        $reviewedMap = [];
        $reviewDataMap = [];

   foreach ($orders as $order) {
    foreach ($order->items as $item) {
        if (!$item->product) continue;

        //  SỬA: dùng đúng cột variant
        $variantId = $item->product_variant_id ?? null;

        //  Lấy đánh giá đúng
        $review = Review::where('order_id', $order->id)
            ->where('product_id', $item->product_id)
            ->where('user_id', auth()->id())
            ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
            ->when(!$variantId, fn($q) => $q->whereNull('variant_id'))
            ->first();

        //  SỬA: key dùng 0 thay vì 'null'
        $key = "{$order->id}-{$item->product_id}-" . ($variantId ?? 0);
        $reviewedMap[$key] = (bool) $review;
        $reviewDataMap[$key] = $review;

        Log::info('purchaseHistory() - Debug review check', [
            'key' => $key,
            'variant_id' => $variantId,
            'review_found' => !is_null($review),
        ]);
    }
}



        return view('client.orders.purchase_history', compact('orders', 'reviewedMap', 'reviewDataMap'), ['coupons' => $coupons,]);
    }


    // ==================== ADDITIONAL SECURITY & OPTIMIZATION ====================

    /**
     * Kiểm tra tính hợp lệ của session data
     */
    protected function validateSessionData($sessionData, $maxAge = 3600)
    {
        if (!$sessionData) {
            return false;
        }

        // Kiểm tra thời gian tạo session (1 giờ)
        $createdAt = $sessionData['created_at'] ?? 0;
        if (now()->timestamp - $createdAt > $maxAge) {
            Log::warning('Session data expired', ['created_at' => $createdAt]);
            return false;
        }

        // Kiểm tra các field bắt buộc
        $requiredFields = ['user_id', 'total_amount', 'cart_items', 'selected_items'];
        foreach ($requiredFields as $field) {
            if (!isset($sessionData[$field])) {
                Log::warning('Missing required field in session', ['field' => $field]);
                return false;
            }
        }

        return true;
    }

    /**
     * Làm sạch session data cũ
     */
    public function cleanupExpiredSessions()
    {
        $expiredKeys = [
            'pending_order',
            'momo_order_code',
            'momo_request_id',
            'vnpay_order_data',
            'vnpay_order_code'
        ];

        foreach ($expiredKeys as $key) {
            if (Session::has($key)) {
                $data = Session::get($key);
                if (is_array($data) && isset($data['created_at'])) {
                    if (now()->timestamp - $data['created_at'] > 3600) {
                        Session::forget($key);
                        Log::info('Cleaned expired session key: ' . $key);
                    }
                }
            }
        }
    }

    /**
     * Xác thực double-spending cho đơn hàng
     */
    protected function preventDoubleSpending($orderCode, $userId)
    {
        $existingOrder = Order::where('code', $orderCode)
            ->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('created_at', '>', now()->subMinutes(5))
                    ->where('is_paid', true);
            })
            ->first();

        if ($existingOrder) {
            throw new \Exception('Đơn hàng đã tồn tại hoặc bạn vừa thực hiện giao dịch');
        }
    }

    /**
     * Kiểm tra stock trước khi tạo đơn hàng
     */
    protected function validateStock($cartItems)
    {
        foreach ($cartItems as $item) {
            if ($item['product_variant_id']) {
                $variant = ProductVariant::find($item['product_variant_id']);
                if (!$variant || $variant->stock < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$item['name']} không đủ hàng trong kho");
                }
            } else {
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$item['name']} không đủ hàng trong kho");
                }
            }
        }
    }

    /**
     * Ghi log chi tiết cho debugging
     */
    protected function logPaymentFlow($step, $data = [])
    {
        Log::info("Payment Flow - {$step}", array_merge([
            'user_id' => auth()->id(),
            'timestamp' => now()->toISOString(),
        ], $data));
    }

    /**
     * Retry mechanism cho API calls
     */
    protected function retryApiCall($callback, $maxRetries = 3, $delay = 1000)
    {
        $attempts = 0;

        while ($attempts < $maxRetries) {
            try {
                return $callback();
            } catch (\Exception $e) {
                $attempts++;
                if ($attempts >= $maxRetries) {
                    throw $e;
                }

                Log::warning("API call failed, retrying... Attempt {$attempts}/{$maxRetries}", [
                    'error' => $e->getMessage()
                ]);

                usleep($delay * $attempts * 1000); // Progressive delay
            }
        }
    }

    /**
     * Webhook handler cho MoMo (tùy chọn)
     */
    public function momoWebhook(Request $request)
    {
        Log::info('MoMo Webhook Received', $request->all());

        // Xử lý tương tự như IPN nhưng có thể có format khác
        return $this->momoIPN($request);
    }

    /**
     * Status check endpoint
     */
    public function checkPaymentStatus($orderCode)
    {
        try {
            $order = Order::where('code', $orderCode)->first();

            if (!$order) {
                return response()->json([
                    'status' => 'not_found',
                    'message' => 'Không tìm thấy đơn hàng'
                ], 404);
            }

            return response()->json([
                'status' => $order->is_paid ? 'paid' : 'pending',
                'order_code' => $order->code,
                'total_amount' => $order->total_amount,
                'payment_method' => $order->payment_id,
                'created_at' => $order->created_at->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi hệ thống'
            ], 500);
        }
    }

    /**
     * Cancel payment
     */
    public function cancelPayment(Request $request)
    {
        try {
            $orderCode = $request->input('order_code');

            // Xóa session data
            Session::forget([
                'pending_order',
                'momo_order_code',
                'momo_request_id',
                'vnpay_order_data',
                'vnpay_order_code'
            ]);

            Log::info('Payment cancelled by user', ['order_code' => $orderCode]);

            return redirect()->route('cart.index')
                ->with('info', 'Đã hủy thanh toán');
        } catch (\Exception $e) {
            Log::error('Cancel payment error: ' . $e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi xảy ra khi hủy thanh toán');
        }
    }
    
    


}
