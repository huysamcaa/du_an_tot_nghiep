<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\CartItem;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\Shared\Order;
use App\Models\Shared\OrderItem;
use App\Models\Coupon;
use App\Models\CouponRestriction;


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
        $userId = auth()->id() ?? 2; // hoặc giả định 2 cho test

        $selectedItems = $request->input('selected_items', []);
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }
        $cartItems = CartItem::where('user_id', $userId)->whereIn('id', $selectedItems)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sản phẩm bạn chọn không tồn tại trong giỏ hàng.');
        }

        $total = $cartItems->sum(function($item) {
            return ($item->product ? $item->product->price : 0) * $item->quantity;
        });

        return view('client.checkout.checkout', compact('cartItems', 'total'));
    }

    public function placeOrder(Request $request)
    {
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
        $userId = auth()->id() ?? 2;
        $selectedItems = $request->input('selected_items', []);
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để đặt hàng.');
        }
        $cartItems = CartItem::where('user_id', $userId)->whereIn('id', $selectedItems)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        try {
            $order = $this->createOrder($request, 3, false);
            
            $paymentData = $this->prepareMomoPaymentData($order);
            $result = $this->sendMomoRequest($paymentData);
            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['payUrl'])) {
                DB::commit();
                return redirect()->away($jsonResult['payUrl']);
            }

            DB::rollBack();
            Log::error('Momo payment failed', ['response' => $jsonResult]);
            return back()->with('error', 'Không thể khởi tạo thanh toán MoMo');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Momo payment exception', ['error' => $e]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
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

    public function momoReturn(Request $request, $order_code)
    {
        try {
            $order = Order::where('code', $order_code)->firstOrFail();
            
            if ($request->resultCode == 0) {
                DB::transaction(function () use ($order) {
                    if (!$order->is_paid) {
                        $order->update(['is_paid' => true]);
                        $this->reduceStock($order);
                    }
                    $this->clearCart($order->user_id);
                });
                
                return redirect()->route('client.orders.show', $order->code)
                    ->with('success', 'Thanh toán MoMo thành công!');
            }
            
            return redirect()->route('client.orders.show', $order->code)
                ->with('error', 'Thanh toán thất bại: ' . ($request->message ?? ''));
        } catch (\Exception $e) {
            Log::error('Momo return error', ['error' => $e]);
            return redirect()->route('client.home')->with('error', 'Có lỗi xảy ra');
        }
    }

    public function momoIPN(Request $request)
    {
        try {
            $input = $request->all();
            Log::info('Momo IPN received', $input);
            
            if (!$this->verifyMomoSignature($input)) {
                return response()->json(['error' => 'Invalid signature'], 403);
            }
            
            if ($input['resultCode'] == 0) {
                $this->processSuccessfulPayment($input);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Momo IPN error', ['error' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function verifyMomoSignature($input)
    {
        $rawHash = "accessKey=" . $input['accessKey'] .
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

        return hash_hmac("sha256", $rawHash, $this->momoConfig['secretKey']) === $input['signature'];
    }

    // Xử lý thanh toán VNPay
    protected function processVNPayPayment(Request $request)
    {
        $userId = auth()->id() ?? 2;
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        try {
            $order = $this->createOrder($request, 4, false); // 4 là payment_id cho VNPay
            
            $paymentData = $this->prepareVNPayPaymentData($order);
            $vnp_Url = $this->buildVNPayUrl($paymentData);
            
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

            CartItem::where('user_id', $userId)->whereIn('id', $selectedItems)->delete();

            DB::commit();
            return redirect()->away($vnp_Url);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay payment error', ['error' => $e]);
            return back()->with('error', 'Không thể khởi tạo thanh toán VNPay: ' . $e->getMessage());
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
            ->where(function($q) {
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
}