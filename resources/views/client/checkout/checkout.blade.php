<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .checkout-container {
            padding: 20px 0;
        }

        .checkout-header {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .checkout-header h2 {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
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

        .address-item,
        .coupon-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        .address-item:last-child,
        .coupon-item:last-child {
            border-bottom: none;
        }

        .address-info,
        .coupon-info {
            flex-grow: 1;
        }

        .address-name,
        .coupon-code {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .address-details,
        .coupon-details {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .address-phone,
        .coupon-value {
            color: #666;
            font-size: 14px;
        }

        .select-address,
        .select-coupon {
            color: #3498db;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
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
            background: linear-gradient(to right, #3498db, #2980b9);
            color: #fff;
            border: none;
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }

        .voucher-input {
            display: flex;
            margin-top: 10px;
        }

        .voucher-input input {
            flex-grow: 1;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 6px 0 0 6px;
            outline: none;
            font-size: 14px;
        }

        .voucher-input button {
            background: linear-gradient(to right, #3498db, #2980b9);
            color: #fff;
            border: none;
            padding: 0 20px;
            border-radius: 0 6px 6px 0;
            cursor: pointer;
            font-weight: 600;
        }

        .add-address-btn,
        .add-coupon-btn {
            color: #3498db;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            margin-top: 10px;
            font-weight: 500;
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
            background: linear-gradient(to right, #3498db, #2980b9);
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 10px;
        }

        .discount-badge {
            background: linear-gradient(to right, #27ae60, #2ecc71);
            color: white;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 10px;
        }

        .address-select,
        .coupon-select {
            width: 100%;
            border: none;
            padding: 0;
            color: #666;
            font-size: 14px;
            background: transparent;
        }

        .coupon-select {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .address-actions,
        .coupon-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .change-address-btn,
        .change-coupon-btn {
            color: #3498db;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .voucher-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .voucher-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .voucher-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .voucher-toggle {
            color: #3498db;
            cursor: pointer;
        }

        .voucher-form {
            display: none;
            margin-top: 10px;
        }

        .voucher-form.active {
            display: block;
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

        .btn-primary {
            background: linear-gradient(to right, #3498db, #2980b9);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-secondary {
            background: #95a5a6;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
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
    <section class="checkout-container">
        <div class="container">
            <div class="checkout-header">
                <h2><i class="fas fa-shopping-cart me-2"></i> Thanh toán đơn hàng</h2>
            </div>

            <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkoutForm">
                @csrf
                <!-- Các trường ẩn giữ nguyên logic cũ -->
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
                                <div class="address-item">
                                    <div class="address-info">
                                        <div class="address-name" id="mainAddressName">
                                            {{ auth()->user()->name ?? '' }}
                                            @if ($defaultAddress)
                                                <span class="default-badge">Mặc định</span>
                                            @endif
                                        </div>
                                        <div class="address-phone" id="mainAddressPhone">(+84)
                                            {{ auth()->user()->phone_number ?? '' }}</div>
                                        <div class="address-details">
                                            <select name="address_id" class="address-select" id="addressSelect"
                                                required>
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
                                    <div class="address-actions">
                                        <div class="change-address-btn" data-bs-toggle="modal"
                                            data-bs-target="#changeAddressModal">
                                            <i class="fas fa-edit me-1"></i> Thay đổi
                                        </div>
                                        <div class="select-address" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal">
                                            <i class="fas fa-plus me-1"></i> Thêm mới
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phần mã giảm giá (thiết kế giống phần địa chỉ) -->
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
                                            <span class="discount-badge" id="appliedCouponBadge"
                                                style="display: none;"></span>
                                        </div>
                                        <div class="coupon-details" id="couponDetails">
                                            Chọn hoặc nhập mã giảm giá để tiết kiệm hơn
                                        </div>
                                        <div class="coupon-value text-success fw-bold" id="couponValue"
                                            style="display: none;">
                                        </div>
                                    </div>
                                    <div class="coupon-actions">
                                        <div class="change-coupon-btn" data-bs-toggle="modal"
                                            data-bs-target="#changeCouponModal">
                                            <i class="fas fa-edit me-1"></i> Chọn Mã
                                        </div>
                                        <div class="select-coupon" data-bs-toggle="modal"
                                            data-bs-target="#addCouponModal">
                                            <i class="fas fa-plus me-1"></i> Nhập Mã
                                        </div>
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
                                            <div class="product-name fw-bold mb-1"
                                                title="{{ $item->product->name }}">
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
                                        alt="VNPay" alt="VNPay" class="payment-icon">
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
                                        <label for="paymentMethod02" class="payment-name">Thanh toán khi nhận
                                            hàng</label>
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
                                    <span class="price-value total"
                                        id="final-total">{{ number_format($total + 30000) }}đ</span>
                                </div>

                                <button type="submit" class="checkout-btn">
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
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Địa Chỉ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAddressForm" action="{{ route('user.addresses.store') }}" method="POST"
                        novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="fullname" class="form-control"
                                    placeholder="Nhập họ và tên" value="{{ old('fullname') }}" required>
                                @error('fullname')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="phone_number" class="form-control"
                                    placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}"
                                    pattern="^0[0-9]{9}$" required>
                                @error('phone_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tỉnh/Thành phố</label>
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
                                <label class="form-label">Phường/Xã</label>
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
                                <label class="form-label">Địa chỉ cụ thể</label>
                                <input type="text" name="address" id="addressInput-add" class="form-control"
                                    placeholder="Số nhà, tên đường..." value="{{ old('address') }}" required>
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
                            <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thay Đổi Địa Chỉ -->
    <div class="modal fade" id="changeAddressModal" tabindex="-1" aria-labelledby="changeAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thay Đổi Địa Chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="address-list">
                        @foreach ($userAddresses as $address)
                            <div class="address-item">
                                <div class="form-check">
                                    <input class="form-check-input address-radio" type="radio"
                                        name="selected_address" value="{{ $address->id }}"
                                        id="address{{ $address->id }}"
                                        {{ $defaultAddress && $defaultAddress->id == $address->id ? 'checked' : '' }}
                                        data-address="{{ $address->address }}"
                                        data-phone="{{ $address->phone_number }}"
                                        data-fullname="{{ $address->fullname }}">
                                    <label class="form-check-label w-100" for="address{{ $address->id }}">
                                        <div class="address-info ms-2">
                                            <div class="address-name">
                                                {{ $address->fullname }}
                                                @if ($address->is_default)
                                                    <span class="default-badge">Mặc định</span>
                                                @endif
                                            </div>
                                            <div class="address-phone">(+84) {{ $address->phone_number }}</div>
                                            <div class="address-details">{{ $address->address }}</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Huỷ</button>
                        <button type="button" class="btn btn-primary" id="saveAddressChange">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Mã Giảm Giá Mới -->
    <div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel"
        aria-hidden="true">
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
                            <input type="text" class="form-control" id="newCouponCode"
                                placeholder="Nhập mã giảm giá">
                            <button class="btn btn-primary" type="button" id="applyNewCoupon">Áp dụng</button>
                        </div>
                        <div id="couponMessage" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thay Đổi Mã Giảm Giá -->
    <div class="modal fade" id="changeCouponModal" tabindex="-1" aria-labelledby="changeCouponModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chọn Mã Giảm Giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="coupon-list">
  {{-- Không dùng mã --}}
  <div class="form-check mb-3">
    <input class="form-check-input coupon-radio" type="radio" name="selected_coupon" value="" id="couponNone" checked>
    <label class="form-check-label w-100" for="couponNone">
      <div class="coupon-option">
        <div class="coupon-code">Không sử dụng mã giảm giá</div>
      </div>
    </label>
  </div>

  {{-- Mã khả dụng --}}
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

  {{-- Mã không khả dụng + lý do --}}
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
                        <button type="button" class="btn btn-primary" id="saveCouponChange">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const subtotal = {{ $total }};
            const shippingFee = 30000;
            let currentTotal = subtotal + shippingFee;
            let currentDiscount = 0;

            // Xử lý chọn địa chỉ
            $('#addressSelect').change(function() {
                const selectedOption = $(this).find('option:selected');
                const address = selectedOption.data('address');
                const phone = selectedOption.data('phone');
                const fullname = selectedOption.data('fullname');

                // Cập nhật các trường ẩn
                $('#hiddenField7').val(address);
                $('#hiddenField5').val(phone ? phone : '{{ auth()->user()->phone_number ?? '' }}');

                if (fullname) {
                    const nameParts = fullname.split(' ');
                    $('#hiddenField1').val(nameParts[0] || '');
                    $('#hiddenField2').val(nameParts.slice(1).join(' ') || '');
                }
                // Thêm đoạn này để cập nhật giao diện
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

                    // Cập nhật select
                    $('#addressSelect').val(addressId);

                    // Cập nhật các trường ẩn
                    $('#hiddenField7').val(address);
                    $('#hiddenField5').val(phone);

                    if (fullname) {
                        const nameParts = fullname.split(' ');
                        $('#hiddenField1').val(nameParts[0] || '');
                        $('#hiddenField2').val(nameParts.slice(1).join(' ') || '');
                    }
                    $('#mainAddressName').text(fullname || '');
                    $('#mainAddressPhone').text('(+84) ' + (phone || ''));

                    // Đóng modal
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
    if (!couponCode) return;

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

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Thêm option mới vào select
                            const newOption = new Option(
                                response.data.address,
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

                            // Nếu là địa chỉ mặc định, chọn nó
                            if (response.data.is_default) {
                                $('#addressSelect').val(response.data.id).trigger('change');
                            }

                            // Đóng modal
                            $('#addAddressModal').modal('hide');

                            // Hiển thị thông báo
                            alert('Thêm địa chỉ thành công!');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            alert('Vui lòng kiểm tra lại thông tin: ' + Object.values(errors)
                                .join('\n'));
                        } else {
                            alert('Có lỗi xảy ra. Vui lòng thử lại!');
                        }
                    }
                });
            });

            // Xử lý modal địa chỉ
            const vnLocationsData = @json($vnLocationsData);
            const provinceSelectAdd = document.getElementById("province-add");
            const wardSelectAdd = document.getElementById("ward-add");

            if (provinceSelectAdd) {
                provinceSelectAdd.addEventListener("change", function() {
                    const selectedProvinceName = this.value;
                    wardSelectAdd.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    if (selectedProvinceName) {
                        const selectedProvince = vnLocationsData.find(p => p.Name === selectedProvinceName);
                        if (selectedProvince) {
                            const allWards = selectedProvince.Districts.flatMap(d => d.Wards);
                            allWards.forEach(ward => {
                                wardSelectAdd.add(new Option(ward.Name, ward.Name));
                            });
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>

