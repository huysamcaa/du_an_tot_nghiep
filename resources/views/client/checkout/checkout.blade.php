<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .pageBannerSection {
            background: #ECF5F4;
            padding: 20px 0;
        }

        .pageBannerContent h2 {
            font-size: 38px;
            color: #52586D;
            font-family: 'Jost', sans-serif;
            margin-bottom: 10px;
        }

        .pageBannerPath a {
            color: #007bff;
            text-decoration: none;
        }

        .pageBannerPath {
            font-size: 14px;
        }

        .checkout-container {
            padding: 20px 0;
        }

        .checkout-card {
            background-color: #fff;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .checkout-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
        }

        .checkout-card-header h3 {
            font-size: 17px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            flex-grow: 1;
        }

        .checkout-card-header .icon {
            color: #3498db;
            margin-right: 12px;
            font-size: 18px;
        }

        .checkout-card-body {
            padding: 20px;
        }

        .address-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            margin-bottom: 15px;
        }

        .address-info {
            flex-grow: 1;
        }

        .address-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .address-details,
        .address-phone {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .product-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        .product-image {
            width: 80px;
            height: 80px;
            border: 1px solid #f2f2f2;
            margin-right: 15px;
            flex-shrink: 0;
            object-fit: contain;
            border-radius: 6px;
        }

        .product-info {
            flex-grow: 1;
        }

        .product-name {
            font-size: 15px;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #2c3e50;
        }

        .product-variant {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .product-price {
            color: #e74c3c;
            font-weight: 600;
        }

        .product-quantity {
            color: #666;
            font-size: 14px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        .price-row:last-child {
            border-bottom: none;
        }

        .price-label {
            color: #666;
        }

        .price-value {
            color: #2c3e50;
            font-weight: 500;
        }

        .price-value.discount {
            color: #27ae60;
        }

        .price-value.total {
            color: #e74c3c;
            font-size: 18px;
            font-weight: 600;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        .payment-method:last-child {
            border-bottom: none;
        }

        .payment-radio {
            margin-right: 15px;
        }

        .payment-info {
            flex-grow: 1;
        }

        .payment-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .payment-desc {
            color: #7f8c8d;
            font-size: 13px;
        }

        .payment-icon {
            width: 40px;
            margin-right: 15px;
        }

        .checkout-btn {
            background: #96b6b8;
            color: #fff;
            border: none;
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            background-color: #82a1a3;
            transform: translateY(-1px);
        }

        .note-input {
            width: 100%;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            resize: vertical;
            min-height: 80px;
            font-size: 14px;
        }

        .default-badge {
            background: #96b6b8;
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 10px;
        }

        .coupon-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        .coupon-info {
            flex-grow: 1;
        }

        .coupon-code {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .coupon-details {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .coupon-value {
            color: #27ae60;
            font-size: 14px;
            font-weight: bold;
        }

        .discount-badge {
            background: #27ae60;
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 10px;
        }

        .address-actions,
        .coupon-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .change-address-btn,
        .select-address,
        .change-coupon-btn,
        .select-coupon {
            background-color: #96b6b8;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 6px 18px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .change-address-btn:hover,
        .select-address:hover,
        .change-coupon-btn:hover,
        .select-coupon:hover {
            background-color: #82a1a3;
            color: white;
            transform: translateY(-1px);
        }

        .modal-header {
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .btn-primary,
        .btn-danger {
            background: #96b6b8;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
        }

        .btn-primary:hover,
        .btn-danger:hover {
            background: #82a1a3;
            color: white;
        }

        .btn-secondary {
            background: #95a5a6;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            color: white;
        }

        .no-address-message {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 15px;
        }

        .coupon-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .coupon-option {
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .coupon-option:hover {
            border-color: #3498db;
            background-color: #f8f9fa;
        }

        .coupon-option.selected {
            border-color: #3498db;
            background-color: #e8f4fd;
        }

        .coupon-desc {
            font-size: 13px;
            color: #7f8c8d;
        }

        .text-success {
            color: #27ae60 !important;
        }
        
    </style>
</head>

<body>
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Thanh toán đơn hàng</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Thanh toán</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout-container">
        <div class="container">
            <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="field1" id="hiddenField1"
                    value="{{ auth()->user()->name ? explode(' ', auth()->user()->name)[0] : '' }}">
                <input type="hidden" name="field2" id="hiddenField2"
                    value="{{ auth()->user()->name ? implode(' ', array_slice(explode(' ', auth()->user()->name), 1)) : '' }}">
                <input type="hidden" name="field4" value="{{ auth()->user()->email ?? '' }}">
                <input type="hidden" name="field5" id="hiddenField5"
                    value="{{ $defaultAddress->phone_number ?? (auth()->user()->phone_number ?? '') }}">
                <input type="hidden" name="field6" value="VN">
                <input type="hidden" name="field7" id="hiddenField7" value="{{ $defaultAddress->address ?? '' }}">
                <input type="hidden" name="momo_pay_url" id="momoPayUrl" value="">

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Phần địa chỉ -->
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <i class="fas fa-map-marker-alt icon"></i>
                                <h3>Địa Chỉ Nhận Hàng</h3>
                            </div>
                            <div class="checkout-card-body">
                                @if($userAddresses->count() > 0)
                                    <div class="address-item border rounded-3 p-3 bg-white shadow-sm d-flex justify-content-between align-items-start">
                                        <div class="address-info flex-grow-1 pe-3">
                                            <div class="address-name fw-bold mb-1" id="mainAddressName">
                                                {{ $defaultAddress ? $defaultAddress->fullname : (auth()->user()->name ?? '') }}
                                                @if ($defaultAddress)
                                                    <span class="default-badge">Mặc định</span>
                                                @endif
                                            </div>
                                            <div class="address-phone text-muted small mb-1" id="mainAddressPhone">
                                                (+84) {{ $defaultAddress ? $defaultAddress->phone_number : (auth()->user()->phone_number ?? '') }}
                                            </div>
                                            <div class="address-details small text-wrap">
                                                <select name="address_id" class="address-select form-select" id="addressSelect" required>
                                                    @foreach ($userAddresses as $address)
                                                        <option value="{{ $address->id }}"
                                                            {{ $defaultAddress && $defaultAddress->id == $address->id ? 'selected' : '' }}
                                                            data-address="{{ $address->address }}"
                                                            data-phone="{{ $address->phone_number }}"
                                                            data-fullname="{{ $address->fullname }}">
                                                            {{ $address->address }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="address-actions text-end">
                                            <button type="button" class="btn change-address-btn" data-bs-toggle="modal" data-bs-target="#changeAddressModal">
                                                <i class="bi bi-pencil-square me-1"></i> Thay đổi
                                            </button>
                                            <button type="button" class="btn select-address" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                                <i class="bi bi-plus-circle me-1"></i> Thêm mới
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="no-address-message">
                                        <p>Bạn chưa có địa chỉ nào. Vui lòng thêm địa chỉ để tiếp tục thanh toán.</p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                            <i class="bi bi-plus-circle me-1"></i> Thêm địa chỉ mới
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Phần mã giảm giá -->
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <i class="fas fa-tag icon"></i>
                                <h3>Mã Giảm Giá</h3>
                            </div>
                            <div class="checkout-card-body">
                                <div class="coupon-item">
                                    <div class="coupon-info">
                                        <div class="coupon-code">
                                            Chưa áp dụng mã giảm giá
                                            <span class="discount-badge" id="appliedCouponBadge" style="display: none;"></span>
                                        </div>
                                        <div class="coupon-details" id="couponDetails">
                                            Chọn hoặc nhập mã giảm giá để tiết kiệm hơn
                                        </div>
                                        <div class="coupon-value text-success fw-bold" id="couponValue" style="display: none;"></div>
                                    </div>
                                    <div class="coupon-actions text-end">
                                        <button type="button" class="btn change-coupon-btn" data-bs-toggle="modal" data-bs-target="#changeCouponModal">
                                            <i class="bi bi-pencil-square me-1"></i> Chọn Mã
                                        </button>
                                        <button type="button" class="btn select-coupon" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                                            <i class="bi bi-plus-circle me-1"></i> Nhập Mã
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="coupon_code" id="selectedCoupon" value="">
                                <input type="hidden" name="coupon_discount" id="couponDiscount" value="0">
                            </div>
                        </div>

                        <!-- Phần sản phẩm -->
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <i class="fas fa-shopping-cart icon"></i>
                                <h3>Sản Phẩm</h3>
                            </div>
                            <div class="checkout-card-body">
                                @foreach ($cartItems as $item)
                                    <div class="product-item align-items-center">
                                        <img src="{{ asset('storage/' . ($item->variant->thumbnail ?? $item->product->thumbnail)) }}"
                                            class="product-image rounded border" alt="Cart Item">
                                        <div class="product-info">
                                            <div class="product-name fw-bold mb-1" title="{{ $item->product->name }}">
                                                {{ $item->product->name }}
                                            </div>
                                            @if ($item->variant)
                                                <div class="product-variant mb-1">
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $item->variant->variant_name ?? 'Chưa cấu hình thuộc tính' }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="product-price mb-1">
                                                {{ number_format($item->variant ? $item->variant->sale_price ?? $item->variant->price : $item->product->price) }}đ
                                            </div>
                                        </div>
                                        <div class="product-quantity text-end ms-3">
                                            <span class="badge bg-secondary">x{{ $item->quantity }}</span>
                                        </div>
                                        <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Phần ghi chú -->
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <i class="fas fa-edit icon"></i>
                                <h3>Ghi Chú Đơn Hàng</h3>
                            </div>
                            <div class="checkout-card-body">
                                <textarea name="field14" class="note-input" placeholder="Lưu ý cho người bán..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Phần thanh toán -->
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <i class="fas fa-credit-card icon"></i>
                                <h3>Phương Thức Thanh Toán</h3>
                            </div>
                            <div class="checkout-card-body">
                                <div class="payment-method">
                                    <input type="radio" value="4" name="paymentMethod" id="paymentMethod04"
                                        class="payment-radio" required>
                                    <img src="https://th.bing.com/th/id/OIP.pn3RUm1xk1HiAxWIgC6CIwHaHa?w=161&h=180&c=7&r=0&o=7&pid=1.7&rm=3"
                                        alt="VNPay" class="payment-icon">
                                    <div class="payment-info">
                                        <label for="paymentMethod04" class="payment-name">VNPay</label>
                                        <div class="payment-desc">
                                            Thanh toán qua cổng thanh toán VNPay (ATM/VISA/MasterCard).
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" value="2" name="paymentMethod" id="paymentMethod02"
                                        class="payment-radio" checked>
                                    <img src="https://th.bing.com/th/id/OIP.sdjsIUIEBcdxUtOdhD8iOAAAAA?w=162&h=180&c=7&r=0&o=7&pid=1.7&rm=3"
                                        alt="COD" class="payment-icon">
                                    <div class="payment-info">
                                        <label for="paymentMethod02" class="payment-name">Thanh toán khi nhận hàng</label>
                                        <div class="payment-desc">
                                            Thanh toán bằng tiền mặt khi giao hàng.
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" value="3" name="paymentMethod" id="paymentMethod03"
                                        class="payment-radio" required>
                                    <img src="https://th.bing.com/th/id/OIP.-DhgkiQDEdoru7CJdZrwEAHaHa?w=169&h=180&c=7&r=0&o=7&pid=1.7&rm=3"
                                        alt="MoMo" class="payment-icon">
                                    <div class="payment-info">
                                        <label for="paymentMethod03" class="payment-name">MoMo</label>
                                        <div class="payment-desc">
                                            Thanh toán qua tài khoản MoMo.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phần tổng kết -->
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <i class="fas fa-receipt icon"></i>
                                <h3>Thông Tin Thanh Toán</h3>
                            </div>
                            <div class="checkout-card-body">
                                <div class="price-row">
                                    <span class="price-label">Tạm tính</span>
                                    <span class="price-value" id="subtotal">{{ number_format($total) }}đ</span>
                                </div>
                                <div class="price-row">
                                    <span class="price-label">Phí vận chuyển</span>
                                    <span class="price-value" id="shipping-fee">30,000đ</span>
                                </div>
                                <div class="price-row" id="discount-row" style="display: none;">
                                    <span class="price-label">Giảm giá</span>
                                    <span class="price-value discount" id="discount-amount">-0đ</span>
                                </div>
                                <div class="price-row"
                                    style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #ddd;">
                                    <span class="price-label">Tổng cộng</span>
                                    <span class="price-value total" id="final-total">{{ number_format($total + 30000) }}đ</span>
                                </div>
                                <button type="submit" class="checkout-btn" id="submitOrderBtn">
                                    <i class="fas fa-check-circle me-2"></i> ĐẶT HÀNG
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Modal Thêm Địa Chỉ Mới -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Địa Chỉ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAddressForm" action="{{ route('user.addresses.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="fullname" class="form-control" placeholder="Họ và tên"
                                    value="{{ old('fullname') }}" required>
                                @error('fullname')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input type="tel" name="phone_number" class="form-control" placeholder="Số điện thoại"
                                    value="{{ old('phone_number') }}" pattern="^0[0-9]{9}$" required>
                                @error('phone_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <select id="province-add" name="province" class="form-select" required>
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                    @foreach ($vnLocationsData as $province)
                                        <option value="{{ $province['Name'] }}"
                                            {{ old('province') == $province['Name'] ? 'selected' : '' }}>
                                            {{ $province['Name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <select id="ward-add" name="ward" class="form-select" required>
                                    <option value="">Chọn Phường/Xã</option>
                                    @if (old('province') && ($selectedProvince = collect($vnLocationsData)->firstWhere('Name', old('province'))))
                                        @foreach (collect($selectedProvince['Districts'])->flatMap(fn($d) => $d['Wards']) as $ward)
                                            <option value="{{ $ward['Name'] }}"
                                                {{ old('ward') == $ward['Name'] ? 'selected' : '' }}>
                                                {{ $ward['Name'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('ward')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <input type="text" name="address" id="addressInput-add" class="form-control"
                                    placeholder="Địa chỉ cụ thể (Số nhà, tên đường...)" value="{{ old('address') }}" required>
                                @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_default" id="is_default" value="1"
                                        class="form-check-input" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                            <button type="submit" class="btn btn-danger">Hoàn thành</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thay Đổi Địa Chỉ -->
    <div class="modal fade" id="changeAddressModal" tabindex="-1" aria-labelledby="changeAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thay Đổi Địa Chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="address-list">
                        @foreach ($userAddresses as $address)
                            <div class="address-item border rounded-3 p-3 bg-white shadow-sm d-flex justify-content-between align-items-start">
                                <div class="form-check flex-grow-1 pe-3">
                                    <input class="form-check-input address-radio" type="radio" name="selected_address"
                                        value="{{ $address->id }}" id="address{{ $address->id }}"
                                        {{ $defaultAddress && $defaultAddress->id == $address->id ? 'checked' : '' }}
                                        data-address="{{ $address->address }}"
                                        data-phone="{{ $address->phone_number }}"
                                        data-fullname="{{ $address->fullname }}">
                                    <label class="form-check-label w-100" for="address{{ $address->id }}">
                                        <div class="address-info ms-2">
                                            <div class="address-name fw-bold mb-1">
                                                {{ $address->fullname }}
                                                @if ($address->is_default)
                                                    <span class="default-badge">Mặc định</span>
                                                @endif
                                            </div>
                                            <div class="address-phone text-muted small mb-1">(+84) {{ $address->phone_number }}</div>
                                            <div class="address-details small text-wrap">{{ $address->address }}</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Huỷ</button>
                        <button type="button" class="btn btn-danger" id="saveAddressChange">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Mã Giảm Giá Mới -->
    <div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Mã Giảm Giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nhập mã giảm giá</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="newCouponCode" placeholder="Nhập mã giảm giá">
                            <button class="btn btn-danger" type="button" id="applyNewCoupon">Áp dụng</button>
                        </div>
                        <div id="couponMessage" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thay Đổi Mã Giảm Giá -->
    <div class="modal fade" id="changeCouponModal" tabindex="-1" aria-labelledby="changeCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn Mã Giảm Giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="coupon-list">
                        <div class="form-check mb-3">
                            <input class="form-check-input coupon-radio" type="radio" name="selected_coupon" value="" id="couponNone" checked>
                            <label class="form-check-label w-100" for="couponNone">
                                <div class="coupon-option">
                                    <div class="coupon-code">Không sử dụng mã giảm giá</div>
                                </div>
                            </label>
                        </div>
                        @foreach(($couponOptions['usable'] ?? []) as $c)
                            <div class="form-check mb-3">
                                <input class="form-check-input coupon-radio" type="radio" name="selected_coupon" value="{{ $c['code'] }}" id="coupon_{{ $c['id'] }}">
                                <label class="form-check-label w-100" for="coupon_{{ $c['id'] }}">
                                    <div class="coupon-option">
                                        <div class="coupon-code">
                                            {{ $c['code'] }}
                                            <span class="discount-badge">Ước tính giảm {{ number_format($c['discount']) }}₫</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                        @foreach(($couponOptions['disabled'] ?? []) as $c)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" disabled>
                                <label class="form-check-label w-100">
                                    <div class="coupon-option opacity-50">
                                        <div class="coupon-code">{{ $c['code'] }}</div>
                                        <div class="coupon-desc text-danger small">{{ $c['reason'] }}</div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Huỷ</button>
                        <button type="button" class="btn btn-danger" id="saveCouponChange">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            const subtotal = {{ $total }};
            const shippingFee = 30000;
            let currentTotal = subtotal + shippingFee;
            let currentDiscount = 0;

            // Xử lý khởi tạo địa chỉ khi trang tải
            function initializeAddress() {
                const selectedOption = $('#addressSelect').find('option:selected');
                if (selectedOption.length > 0) {
                    const address = selectedOption.data('address');
                    const phone = selectedOption.data('phone');
                    const fullname = selectedOption.data('fullname');
                    
                    $('#hiddenField7').val(address || '');
                    $('#hiddenField5').val(phone || '{{ auth()->user()->phone_number ?? '' }}');
                    
                    if (fullname) {
                        const nameParts = fullname.split(' ');
                        $('#hiddenField1').val(nameParts[0] || '');
                        $('#hiddenField2').val(nameParts.slice(1).join(' ') || '');
                    }
                    
                    $('#mainAddressName').text(fullname || '{{ auth()->user()->name ?? '' }}');
                    $('#mainAddressPhone').text('(+84) ' + (phone || '{{ auth()->user()->phone_number ?? '' }}'));
                } else {
                    $('#hiddenField7').val('{{ $defaultAddress->address ?? '' }}');
                    $('#hiddenField5').val('{{ $defaultAddress->phone_number ?? (auth()->user()->phone_number ?? '') }}');
                }
            }

            initializeAddress();

            // Xử lý chọn địa chỉ
            $('#addressSelect').change(function() {
                const selectedOption = $(this).find('option:selected');
                const address = selectedOption.data('address');
                const phone = selectedOption.data('phone');
                const fullname = selectedOption.data('fullname');

                $('#hiddenField7').val(address);
                $('#hiddenField5').val(phone ? phone : '{{ auth()->user()->phone_number ?? '' }}');

                if (fullname) {
                    const nameParts = fullname.split(' ');
                    $('#hiddenField1').val(nameParts[0] || '');
                    $('#hiddenField2').val(nameParts.slice(1).join(' ') || '');
                }
                
                $('#mainAddressName').text(fullname || '');
                $('#mainAddressPhone').text('(+84) ' + (phone || ''));
            });

            // Xử lý thay đổi địa chỉ
            $('#saveAddressChange').on('click', function() {
                const selectedAddress = $('input[name="selected_address"]:checked');
                if (selectedAddress.length > 0) {
                    const addressId = selectedAddress.val();
                    const address = selectedAddress.data('address');
                    const phone = selectedAddress.data('phone');
                    const fullname = selectedAddress.data('fullname');

                    $('#addressSelect').val(addressId);
                    $('#hiddenField7').val(address);
                    $('#hiddenField5').val(phone);

                    if (fullname) {
                        const nameParts = fullname.split(' ');
                        $('#hiddenField1').val(nameParts[0] || '');
                        $('#hiddenField2').val(nameParts.slice(1).join(' ') || '');
                    }
                    
                    $('#mainAddressName').text(fullname || '');
                    $('#mainAddressPhone').text('(+84) ' + (phone || ''));

                    $('#changeAddressModal').modal('hide');
                }
            });

            // Xử lý thay đổi mã giảm giá
            $('#saveCouponChange').on('click', function() {
                const selectedCoupon = $('input[name="selected_coupon"]:checked');
                const couponCode = selectedCoupon.val();

                if (!couponCode) {
                    applyCouponUI(null, 0, null);
                    $('#changeCouponModal').modal('hide');
                    return;
                }

                const selectedItems = $('input[name="selected_items[]"]').map(function() {
                    return $(this).val();
                }).get();

                $.post('{{ route('checkout.coupons.preview') }}', {
                    _token: '{{ csrf_token() }}',
                    code: couponCode,
                    selected_items: selectedItems
                }).done(function(res) {
                    applyCouponUI(couponCode, res.discount, res.coupon);
                    $('#changeCouponModal').modal('hide');
                }).fail(function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Không thể áp dụng mã giảm giá';
                    alert(msg);
                });
            });

            // Xử lý áp dụng mã giảm giá mới
            $('#applyNewCoupon').on('click', function() {
                const couponCode = $('#newCouponCode').val().trim();
                if (!couponCode) {
                    $('#couponMessage').addClass('text-danger').text('Vui lòng nhập mã giảm giá');
                    return;
                }

                const selectedItems = $('input[name="selected_items[]"]').map(function(){return $(this).val();}).get();
                const couponMessage = $('#couponMessage').removeClass('text-danger text-success').html('Đang kiểm tra...');

                $.post('{{ route('checkout.coupons.preview') }}', {
                    _token: '{{ csrf_token() }}',
                    code: couponCode,
                    selected_items: selectedItems
                }).done(function(res) {
                    couponMessage.addClass('text-success').html('<i class="fas fa-check-circle"></i> Áp dụng mã thành công!');
                    applyCouponUI(couponCode, res.discount, res.coupon);
                    setTimeout(() => $('#addCouponModal').modal('hide'), 700);
                }).fail(function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Mã giảm giá không hợp lệ';
                    couponMessage.addClass('text-danger').text(msg);
                });
            });

            // Hàm áp dụng mã giảm giá
            function applyCouponUI(code, discount, couponMeta) {
                const shippingFee = 30000;
                const subtotal = {{ $total }};

                if (code) {
                    $('#appliedCouponBadge').text(code).show();
                    $('#couponDetails').html(
                        couponMeta ?
                        (couponMeta.discount_type === 'percent' ?
                            `Giảm ${couponMeta.discount_value}%` :
                            `Giảm ${Number(couponMeta.discount_value).toLocaleString('vi-VN')}₫`) :
                        'Đã áp dụng mã'
                    );
                    $('#couponValue').text(`-${Math.round(discount).toLocaleString('vi-VN')}₫`).show();
                    $('#discount-row').show();
                    $('#discount-amount').text('-' + Math.round(discount).toLocaleString('vi-VN') + 'đ');
                    $('#selectedCoupon').val(code);
                    $('#couponDiscount').val(Math.round(discount));
                } else {
                    $('#appliedCouponBadge').hide();
                    $('#couponDetails').text('Chọn hoặc nhập mã giảm giá để tiết kiệm hơn');
                    $('#couponValue').hide();
                    $('#discount-row').hide();
                    $('#selectedCoupon').val('');
                    $('#couponDiscount').val(0);
                    discount = 0;
                }

                const final = Math.max(0, subtotal + shippingFee - (discount || 0));
                $('#final-total').text(final.toLocaleString('vi-VN') + 'đ');
            }

            // Xử lý khi thêm địa chỉ thành công
            $('#addAddressForm').on('submit', function(e) {
                e.preventDefault();
                
                // Kiểm tra dữ liệu phía client
                const fullnameInput = $('[name="fullname"]');
                const phoneInput = $('[name="phone_number"]');
                const provinceInput = $('[name="province"]');
                const wardInput = $('[name="ward"]');
                const addressInput = $('[name="address"]');
                const phonePattern = /^0[0-9]{9}$/;
                
                $('.error-message').remove();
                let hasError = false;

                if (!fullnameInput.val().trim()) {
                    fullnameInput.after('<div class="text-danger small mt-1 error-message">Họ và tên là bắt buộc.</div>');
                    hasError = true;
                }
                if (!phonePattern.test(phoneInput.val())) {
                    phoneInput.after('<div class="text-danger small mt-1 error-message">Số điện thoại phải bắt đầu bằng 0 và có 10 chữ số.</div>');
                    hasError = true;
                }
                if (!provinceInput.val()) {
                    provinceInput.after('<div class="text-danger small mt-1 error-message">Vui lòng chọn tỉnh/thành phố.</div>');
                    hasError = true;
                }
                if (!wardInput.val()) {
                    wardInput.after('<div class="text-danger small mt-1 error-message">Vui lòng chọn phường/xã.</div>');
                    hasError = true;
                }
                if (!addressInput.val().trim()) {
                    addressInput.after('<div class="text-danger small mt-1 error-message">Địa chỉ cụ thể là bắt buộc.</div>');
                    hasError = true;
                }

                if (hasError) {
                    return;
                }

                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        submitBtn.prop('disabled', false).html(originalText);
                        
                        if (response && response.success && response.data) {
                            const newOption = new Option(
                                response.data.full_address || response.data.address,
                                response.data.id,
                                false,
                                response.data.is_default
                            );

                            $(newOption).attr({
                                'data-address': response.data.address,
                                'data-phone': response.data.phone_number,
                                'data-fullname': response.data.fullname
                            });

                            $('#addressSelect').append(newOption);

                            if (response.data.is_default) {
                                $('#addressSelect').val(response.data.id).trigger('change');
                            }

                            $('#addAddressForm')[0].reset();
                            $('#ward-add').html('<option value="">Chọn Phường/Xã</option>');
                            $('#province-add').val('');
                            $('.error-message').remove();
                            $('#addAddressModal').modal('hide');
                            alert('Thêm địa chỉ thành công!');
                        } else {
                            alert('Có lỗi xảy ra: ' + (response.message || 'Dữ liệu trả về không hợp lệ. Vui lòng kiểm tra server.'));
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(originalText);
                        $('.error-message').remove();
                        
                        let errorMessage = 'Có lỗi xảy ra. Vui lòng thử lại!';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            const errorMessages = [];
                            for (const field in errors) {
                                const input = $(`[name="${field}"]`);
                                if (input.length) {
                                    input.after(`<div class="text-danger small mt-1 error-message">${errors[field][0]}</div>`);
                                }
                                errorMessages.push(errors[field][0]);
                            }
                            if (errorMessages.length > 0) {
                                errorMessage = 'Vui lòng kiểm tra: \n' + errorMessages.join('\n');
                            }
                        } else if (xhr.status === 419) {
                            errorMessage = 'Phiên làm việc hết hạn. Vui lòng tải lại trang!';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Lỗi server: ' + (xhr.responseJSON?.message || 'Vui lòng kiểm tra log server.');
                        } else {
                            errorMessage = 'Bạn Chắc Chắn Đây Là Địa Chỉ Của Mình Chứ?';
                        }
                        
                        alert(errorMessage);
                    }
                });
            });

            // Xử lý modal địa chỉ
            const vnLocationsData = @json($vnLocationsData);
            const provinceSelectAdd = document.getElementById("province-add");
            const wardSelectAdd = document.getElementById("ward-add");

            if (!vnLocationsData || !Array.isArray(vnLocationsData)) {
                console.error('Dữ liệu vnLocationsData không hợp lệ:', vnLocationsData);
                alert('Lỗi dữ liệu tỉnh/thành phố. Vui lòng liên hệ quản trị viên.');
                return;
            }

            if (provinceSelectAdd) {
                provinceSelectAdd.addEventListener("change", function() {
                    const selectedProvinceName = this.value;
                    wardSelectAdd.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    if (selectedProvinceName) {
                        const selectedProvince = vnLocationsData.find(p => p.Name === selectedProvinceName);
                        if (selectedProvince && selectedProvince.Districts) {
                            const allWards = selectedProvince.Districts.flatMap(d => d.Wards || []);
                            if (allWards.length === 0) {
                                wardSelectAdd.innerHTML = '<option value="">Không có phường/xã</option>';
                            } else {
                                allWards.forEach(ward => {
                                    if (ward && ward.Name) {
                                        wardSelectAdd.add(new Option(ward.Name, ward.Name));
                                    }
                                });
                            }
                        } else {
                            wardSelectAdd.innerHTML = '<option value="">Không có phường/xã</option>';
                        }
                    }
                });
            }

            // Kiểm tra địa chỉ trước khi submit
            $('#checkoutForm').on('submit', function(e) {
                if ($('#addressSelect').val() === '' || $('#addressSelect').find('option').length === 0) {
                    e.preventDefault();
                    alert('Vui lòng thêm địa chỉ giao hàng trước khi thanh toán');
                    $('#addAddressModal').modal('show');
                }
            });

            // Reset form khi modal địa chỉ đóng
            $('#addAddressModal').on('hidden.bs.modal', function() {
                $('#addAddressForm')[0].reset();
                $('#ward-add').html('<option value="">Chọn Phường/Xã</option>');
                $('#province-add').val('');
                $('.error-message').remove();
            });

            // Xử lý lỗi validation khi tải trang
            @if ($errors->any())
                let addModalEl = document.getElementById('addAddressModal');
                let addModal = new bootstrap.Modal(addModalEl);
                addModal.show();
            @endif
        });
    </script>
    <script>
    // Xử lý submit form thêm địa chỉ bằng AJAX
    $(function() {
        $('#addAddressForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            const method = form.attr('method') || 'POST';

            // Xóa thông báo lỗi cũ
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback.ajax-error').remove();

            $.ajax({
                url: url,
                method: method,
                data: form.serialize(),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    // Mong controller trả JSON { success: true, address: {...} }
                    if (response && response.success) {
                        // Thêm option mới vào select địa chỉ và chọn nó
                        const addr = response.address;
                        const option = $('<option>')
                            .val(addr.id)
                            .text(addr.address)
                            .attr('data-address', addr.address)
                            .attr('data-phone', addr.phone_number)
                            .attr('data-fullname', addr.fullname);
                        $('#addressSelect').append(option).val(addr.id).trigger('change');

                        // Cập nhật hiển thị tên/sdt/ẩn
                        $('#mainAddressName').text(addr.fullname || '');
                        $('#mainAddressPhone').text('(+84) ' + (addr.phone_number || ''));
                        $('#hiddenField7').val(addr.address || '');
                        $('#hiddenField5').val(addr.phone_number || '');
                        if (addr.fullname) {
                            const parts = addr.fullname.split(' ');
                            $('#hiddenField1').val(parts[0] || '');
                            $('#hiddenField2').val(parts.slice(1).join(' ') || '');
                        }

                        // Đóng modal (Bootstrap 5)
                        const addModalEl = document.getElementById('addAddressModal');
                        const addModal = bootstrap.Modal.getInstance(addModalEl) || new bootstrap.Modal(addModalEl);
                        addModal.hide();

                        // Reset form
                        form[0].reset();

                        // Thông báo
                        if (window.toastr) toastr.success(response.message || 'Thêm địa chỉ thành công');
                    } else {
                        // Nếu controller trả redirect HTML, fallback reload
                        if (response && response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.reload();
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        // Hiển thị error tương ứng dưới input
                        Object.keys(errors).forEach(function (key) {
                            const input = form.find('[name="' + key + '"]');
                            if (!input.length) {
                                // nếu field dạng nested (eg: province[id]) thử select bằng contains
                                const alt = form.find('[name^="' + key + '"]');
                                if (alt.length) input = alt.first();
                            }
                            if (input.length) {
                                input.addClass('is-invalid');
                                const msg = $('<div class="invalid-feedback ajax-error"></div>').text(errors[key][0]);
                                input.after(msg);
                            }
                        });
                    } else {
                        console.error('Add address error', xhr);
                        if (window.toastr) toastr.error('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                }
            });
        });
    });
</script>
</body>
</html>