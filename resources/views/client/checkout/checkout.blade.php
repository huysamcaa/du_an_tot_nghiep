@extends('client.layouts.app')

@section('content')
<style>
    .checkout-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
        background: #ffffff;
        min-height: 100vh;
    }
    
    .checkout-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .checkout-header h1 {
        color: #333;
        font-size: 2.5rem;
        font-weight: 300;
        margin-bottom: 10px;
    }
    
    .checkout-steps {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-bottom: 50px;
        flex-wrap: wrap;
    }
    
    .checkout-step {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6c757d;
        font-size: 0.95rem;
    }
    
    .checkout-step.active {
        color: #9ebbbd;
    }
    
    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .checkout-step.active .step-number {
        background: #9ebbbd;
        color: white;
    }
    
    .checkout-main {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
        align-items: start;
    }
    
    .checkout-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 25px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #9ebbbd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #9ebbbd;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 15px;
    }
    
    .checkbox-input {
        width: 18px;
        height: 18px;
        accent-color: #9ebbbd;
    }
    
    .add-address-btn, .add-coupon-btn {
        background: transparent;
        border: 2px dashed #9ebbbd;
        color: #9ebbbd;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
    }
    
    .add-address-btn:hover, .add-coupon-btn:hover {
        background: #9ebbbd;
        color: white;
    }
    
    .shipping-options {
        display: grid;
        gap: 15px;
    }
    
    .shipping-option {
        padding: 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .shipping-option:hover {
        border-color: #9ebbbd;
    }
    
    .shipping-option.selected {
        border-color: #9ebbbd;
        background: #f8f9ff;
    }
    
    .shipping-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .shipping-price {
        font-weight: 600;
        color: #333;
    }
    
    .shipping-time {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .payment-methods {
        display: grid;
        gap: 12px;
    }
    
    .payment-method {
        padding: 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .payment-method:hover {
        border-color: #9ebbbd;
    }
    
    .payment-method.selected {
        border-color: #9ebbbd;
        background: #f8f9ff;
    }
    
    .payment-method input[type="radio"] {
        accent-color: #9ebbbd;
    }
    
    .payment-desc {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 8px;
        display: none;
    }
    
    .payment-method.selected .payment-desc {
        display: block;
    }
    
    .order-summary {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        position: sticky;
        top: 20px;
    }
    
    .summary-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 25px;
        color: #333;
    }
    
    .cart-items {
        margin-bottom: 25px;
    }
    
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .item-info {
        flex: 1;
    }
    
    .item-name {
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }
    
    .item-variant {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .item-price {
        font-weight: 600;
        color: #333;
    }
    
    .coupon-section {
        margin-bottom: 25px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .coupon-title {
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .coupon-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        margin-bottom: 10px;
        background: white;
    }
    
    .coupon-info {
        flex: 1;
    }
    
    .coupon-code {
        font-weight: 600;
        color: #2a6b5b;
    }
    
    .coupon-desc {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .coupon-remove {
        color: #dc3545;
        cursor: pointer;
        font-size: 1.2rem;
    }
    
    .summary-totals {
        border-top: 2px solid #f1f3f4;
        padding-top: 20px;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }
    
    .total-row.final {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        padding-top: 15px;
        border-top: 1px solid #f1f3f4;
        margin-top: 15px;
    }
    
    .place-order-btn {
        width: 100%;
        background: linear-gradient(135deg, #9ebbbd, #9ebbbd);
        color: white;
        border: none;
        padding: 16px 24px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 25px;
    }
    
    .place-order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.3);
    }
    
    .text-danger {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }
    
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .modal-header {
        padding: 25px 30px 20px;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .modal-body {
        padding: 20px 30px 25px;
    }
    
    .modal-footer {
        padding: 20px 30px 25px;
        border-top: 1px solid #f1f3f4;
    }
    
    .btn-primary {
        background: #9ebbbd;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
    }
    
    .coupon-list {
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 20px;
    }
    
    .coupon-card {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .coupon-card:hover {
        border-color: #9ebbbd;
        background: #f8f9ff;
    }
    
    .coupon-card.selected {
        border-color: #9ebbbd;
        background: #f0f9f7;
    }
    
    .coupon-card-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .coupon-card-code {
        font-weight: 600;
        color: #2a6b5b;
    }
    
    .coupon-card-discount {
        font-weight: 600;
        color: #28a745;
    }
    
    .coupon-card-body {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .coupon-card-footer {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 10px;
        font-style: italic;
    }
    
    @media (max-width: 768px) {
        .checkout-main {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .checkout-steps {
            gap: 15px;
        }
        
        .checkout-section, .order-summary {
            padding: 20px;
        }
    }
</style>

<!-- BEGIN: Page Banner Section -->
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Mua sắm với FreshFit</h2>
                    <div class="pageBannerPath">
                        <a href="{{route('client.home')}}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Thanh toán</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>Thanh toán đơn hàng</h1>
        <div class="checkout-steps">
            <div class="checkout-step active">
                <div class="step-number">1</div>
                <span>Địa chỉ giao hàng</span>
            </div>
            <div class="checkout-step">
                <div class="step-number">2</div>
                <span>Phương thức vận chuyển</span>
            </div>
            <div class="checkout-step">
                <div class="step-number">3</div>
                <span>Thanh toán</span>
            </div>
            <div class="checkout-step">
                <div class="step-number">4</div>
                <span>Xác nhận đơn hàng</span>
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf
        <div class="checkout-main">
            <div class="checkout-left">
                <!-- Shipping Address -->
                <div class="checkout-section">
                    <h3 class="section-title">
                        <span class="section-number">1</span>
                        Địa chỉ giao hàng
                    </h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="first_name" class="form-control" placeholder="Họ *" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" class="form-control" placeholder="Tên *" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" 
                                   placeholder="Địa chỉ email *" 
                                   value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone" class="form-control" 
                                   placeholder="Số điện thoại *" 
                                   value="{{ auth()->user()->phone ?? '' }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <select name="country" class="form-control form-select">
                            <option value="VN" selected>Việt Nam</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <select name="address" class="form-control form-select address-select" id="addressSelect" required>
                            <option value="">Chọn địa chỉ giao hàng *</option>
                            @foreach($userAddresses as $address)
                                <option value="{{ $address->address }}"
                                    {{ old('address', $defaultAddress->address ?? '') == $address->address ? 'selected' : '' }}
                                    data-phone="{{ $address->phone }}"
                                    data-fullname="{{ $address->fullname }}">
                                    {{ $address->address }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <textarea name="note" class="form-control" 
                                  placeholder="Ghi chú đơn hàng (tùy chọn)" rows="4"></textarea>
                    </div>
                    
                    <button type="button" class="add-address-btn" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <span>+</span>
                        Thêm địa chỉ mới
                    </button>
                </div>

                <!-- Shipping Methods -->
                <div class="checkout-section">
                    <h3 class="section-title">
                        <span class="section-number">2</span>
                        Phương thức vận chuyển
                    </h3>
                    
                    <div class="shipping-options">
                        <div class="shipping-option selected" data-price="30000">
                            <div class="shipping-info">
                                <div class="shipping-price">30.000₫</div>
                                <div class="shipping-time">Giao hàng tiêu chuẩn</div>
                            </div>
                            <div class="shipping-time">3-5 ngày</div>
                        </div>
                        <div class="shipping-option" data-price="50000">
                            <div class="shipping-info">
                                <div class="shipping-price">50.000₫</div>
                                <div class="shipping-time">Giao hàng nhanh</div>
                            </div>
                            <div class="shipping-time">1-2 ngày</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="checkout-section">
                    <h3 class="section-title">
                        <span class="section-number">3</span>
                        Phương thức thanh toán
                    </h3>
                    
                    <div class="payment-methods">
                        <label class="payment-method selected">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div>
                                <div style="font-weight: 500;">Thanh toán khi nhận hàng</div>
                                <div class="payment-desc">Thanh toán bằng tiền mặt khi giao hàng</div>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="vnpay">
                            <div>
                                <div style="font-weight: 500;">VNPay</div>
                                <div class="payment-desc">Thanh toán qua cổng thanh toán VNPay (ATM/VISA/MasterCard)</div>
                            </div>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="momo">
                            <div>
                                <div style="font-weight: 500;">MoMo</div>
                                <div class="payment-desc">Thanh toán qua tài khoản MoMo</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3 class="summary-title">Tóm tắt đơn hàng</h3>
                
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-item">
                            <input type="hidden" name="cart_items[]" value="{{ $item->id }}">
                            <div class="item-info">
                                <div class="item-name">{{ $item->product->name }}</div>
                                @if($item->variant)
                                    <div class="item-variant">{{ $item->variant->sku }}</div>
                                @endif
                            </div>
                            <div class="item-price">
                                @php
                                    $price = $item->variant ? 
                                            ($item->variant->sale_price ?? $item->variant->price) : 
                                            $item->product->price;
                                    $itemTotal = $price * $item->quantity;
                                @endphp
                                {{ number_format($itemTotal) }}₫
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="coupon-section">
                    <div class="coupon-title">Mã giảm giá</div>
                    <div id="applied-coupons">
                        <!-- Các mã giảm giá đã áp dụng sẽ hiển thị ở đây -->
                    </div>
                    <button type="button" class="add-coupon-btn" data-bs-toggle="modal" data-bs-target="#couponModal">
                        <span>+</span>
                        Chọn mã giảm giá
                    </button>
                    <input type="hidden" name="coupon_code" id="coupon_code_input">
                </div>
                
                <div class="summary-totals">
                    <div class="total-row">
                        <span>Tổng tiền hàng</span>
                        <span id="subtotal">{{ number_format($total) }}₫</span>
                    </div>
                    <div class="total-row">
                        <span>Phí vận chuyển</span>
                        <span id="shipping-fee">30.000₫</span>
                    </div>
                    <div class="total-row" id="discount-row" style="display: none;">
                        <span>Giảm giá</span>
                        <span id="discount-amount" style="color: #dc3545;">-0₫</span>
                    </div>
                    <div class="total-row final">
                        <span>Tổng thanh toán</span>
                        <span id="final-total">{{ number_format($total + 30000) }}₫</span>
                    </div>
                </div>
                
                <button type="submit" class="place-order-btn">
                    Đặt hàng
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Thêm Địa chỉ -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAddressForm" action="{{ route('user.addresses.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="fullname" class="form-control" 
                                   placeholder="Họ và tên *" value="{{ old('fullname') }}" required>
                            @error('fullname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" class="form-control" 
                                   placeholder="Số điện thoại *" value="{{ old('phone') }}" 
                                   pattern="^0[0-9]{9}$" required>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <select id="province-add" name="province" class="form-control form-select" required>
                                <option value="">Chọn Tỉnh/Thành phố *</option>
                                @foreach($vnLocationsData as $province)
                                    <option value="{{ $province['Name'] }}" 
                                            {{ old('province') == $province['Name'] ? 'selected' : '' }}>
                                        {{ $province['Name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <select id="ward-add" name="ward" class="form-control form-select" required>
                                <option value="">Chọn Phường/Xã *</option>
                                @if(old('province') && $selectedProvince = collect($vnLocationsData)->firstWhere('Name', old('province')))
                                    @foreach(collect($selectedProvince['Districts'])->flatMap(fn($d) => $d['Wards']) as $ward)
                                        <option value="{{ $ward['Name'] }}" 
                                                {{ old('ward') == $ward['Name'] ? 'selected' : '' }}>
                                            {{ $ward['Name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('ward')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" name="address" class="form-control" 
                               placeholder="Địa chỉ cụ thể (Số nhà, tên đường...) *" 
                               value="{{ old('address') }}" required>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_default" id="is_default" 
                               value="1" class="checkbox-input" {{ old('is_default') ? 'checked' : '' }}>
                        <label for="is_default">Đặt làm địa chỉ mặc định</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button type="submit" form="addAddressForm" class="btn btn-primary">Hoàn thành</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chọn Khuyến Mãi -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn mã giảm giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="coupon-list">
                    @foreach($coupons as $coupon)
                        <div class="coupon-card" data-coupon-id="{{ $coupon->id }}" 
                             data-code="{{ $coupon->code }}"
                             data-discount-type="{{ $coupon->discount_type }}"
                             data-discount-value="{{ $coupon->discount_value }}"
                             data-max-discount="{{ $coupon->restriction->max_discount_value ?? '' }}"
                             data-min-order="{{ $coupon->restriction->min_order_value ?? '' }}">
                            <div class="coupon-card-header">
                                <div class="coupon-card-code">{{ $coupon->code }}</div>
                                <div class="coupon-card-discount">
                                    @if($coupon->discount_type == 'percent')
                                        Giảm {{ $coupon->discount_value }}%
                                    @else
                                        Giảm {{ number_format($coupon->discount_value) }}₫
                                    @endif
                                </div>
                            </div>
                            <div class="coupon-card-body">
                                {{ $coupon->description ?? 'Không có mô tả' }}
                            </div>
                            <div class="coupon-card-footer">
                                @isset($coupon->end_date)
                                    HSD: {{ $coupon->end_date->format('d/m/Y') }}
                                @else
                                    Không có hạn sử dụng
                                @endisset
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" id="applyCouponBtn" class="btn btn-primary">Áp dụng</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    const subtotal = {{ $total }};
    let currentShippingFee = 30000;
    let selectedCoupon = null;
    let currentDiscount = 0;
    
    // Cập nhật tổng tiền ban đầu
    updateFinalTotal();
    
    // Xử lý thay đổi địa chỉ
    $('.address-select').change(function() {
        const selectedOption = $(this).find('option:selected');
        $('input[name="phone"]').val(selectedOption.data('phone') || '');
        const fullname = selectedOption.data('fullname') || '';
        const nameParts = fullname.split(' ');
        $('input[name="first_name"]').val(nameParts[0] || '');
        $('input[name="last_name"]').val(nameParts.slice(1).join(' ') || '');
    });
    
    // Xử lý phương thức vận chuyển
    $('.shipping-option').click(function() {
        $('.shipping-option').removeClass('selected');
        $(this).addClass('selected');
        currentShippingFee = parseInt($(this).data('price'));
        $('#shipping-fee').text(number_format(currentShippingFee) + '₫');
        updateFinalTotal();
    });
    
    // Xử lý phương thức thanh toán
    $('.payment-method input[type="radio"]').change(function() {
        $('.payment-method').removeClass('selected');
        $(this).closest('.payment-method').addClass('selected');
    });
    
    // Xử lý chọn coupon
    $('.coupon-card').click(function() {
        $('.coupon-card').removeClass('selected');
        $(this).addClass('selected');
        selectedCoupon = {
            id: $(this).data('coupon-id'),
            code: $(this).data('code'),
            discount_type: $(this).data('discount-type'),
            discount_value: parseFloat($(this).data('discount-value')),
            max_discount: parseFloat($(this).data('max-discount')) || 0,
            min_order: parseFloat($(this).data('min-order')) || 0
        };
    });
    
    // Áp dụng coupon
    $('#applyCouponBtn').click(function() {
        if (!selectedCoupon) {
            return;
        }
        
        // Kiểm tra điều kiện đơn hàng tối thiểu
        if (selectedCoupon.min_order > 0 && subtotal < selectedCoupon.min_order) {
            return;
        }
        
        // Tính toán giảm giá
        currentDiscount = 0;
        
        if (selectedCoupon.discount_type === 'percent') {
            currentDiscount = (subtotal * selectedCoupon.discount_value) / 100;
            if (selectedCoupon.max_discount > 0 && currentDiscount > selectedCoupon.max_discount) {
                currentDiscount = selectedCoupon.max_discount;
            }
        } else {
            currentDiscount = selectedCoupon.discount_value;
        }
        
        if (currentDiscount > subtotal) {
            currentDiscount = subtotal;
        }
        
        // Hiển thị coupon đã áp dụng
        const couponHtml = `
            <div class="coupon-item" data-coupon-id="${selectedCoupon.id}">
                <div class="coupon-info">
                    <div class="coupon-code">${selectedCoupon.code}</div>
                    <div class="coupon-desc">
                        ${selectedCoupon.discount_type === 'percent' ? 
                          `Giảm ${selectedCoupon.discount_value}%` : 
                          `Giảm ${number_format(selectedCoupon.discount_value)}₫`}
                    </div>
                </div>
                <div class="coupon-remove" onclick="removeCoupon(this)">×</div>
            </div>
        `;
        
        $('#applied-coupons').html(couponHtml);
        $('#coupon_code_input').val(selectedCoupon.code);
        
        // Cập nhật tổng tiền
        $('#discount-row').show();
        $('#discount-amount').text('-' + number_format(Math.round(currentDiscount)) + '₫');
        updateFinalTotal(currentDiscount);
        
        // Đóng modal
        $('#couponModal').modal('hide');
        $('.modal-backdrop').remove();
    });
    
    // Hàm xóa coupon
    window.removeCoupon = function(element) {
        $(element).closest('.coupon-item').remove();
        $('#discount-row').hide();
        currentDiscount = 0;
        updateFinalTotal(0);
        selectedCoupon = null;
        $('#coupon_code_input').val('');
    };
    
    function updateFinalTotal(discount = 0) {
        const finalTotal = subtotal + currentShippingFee - discount;
        $('#final-total').text(number_format(Math.round(finalTotal)) + '₫');
    }
    
    function number_format(number) {
        return new Intl.NumberFormat('vi-VN').format(number);
    }
    
    // Xử lý form thêm địa chỉ
    const vnLocationsData = @json($vnLocationsData);
    
    $('#province-add').change(function() {
        const selectedProvinceName = this.value;
        const wardSelect = $('#ward-add');
        wardSelect.html('<option value="">Chọn Phường/Xã *</option>');
        
        if (selectedProvinceName) {
            const selectedProvince = vnLocationsData.find(p => p.Name === selectedProvinceName);
            if (selectedProvince) {
                const allWards = selectedProvince.Districts.flatMap(d => d.Wards);
                allWards.forEach(ward => {
                    wardSelect.append(new Option(ward.Name, ward.Name));
                });
            }
        }
    });
    
    // Xử lý submit form thêm địa chỉ
    $('#addAddressForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Hiển thị loading
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Tạo địa chỉ đầy đủ
                const fullAddress = formData.get('address') + ', ' + formData.get('ward') + ', ' + formData.get('province');
                
                // Thêm option mới vào select
                const newOption = new Option(fullAddress, fullAddress, false, true);
                $(newOption).attr({
                    'data-phone': formData.get('phone'),
                    'data-fullname': formData.get('fullname')
                });
                $('#addressSelect').append(newOption);
                
                // Cập nhật thông tin form chính
                const nameParts = formData.get('fullname').split(' ');
                $('input[name="first_name"]').val(nameParts[0] || '');
                $('input[name="last_name"]').val(nameParts.slice(1).join(' ') || '');
                $('input[name="phone"]').val(formData.get('phone'));
                
                // Đóng modal và reset form
                $('#addAddressModal').modal('hide');
                $('#addAddressForm')[0].reset();
                $('#ward-add').html('<option value="">Chọn Phường/Xã *</option>');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    // Xóa lỗi cũ
                    $('.text-danger').remove();
                    $('.is-invalid').removeClass('is-invalid');
                    
                    // Hiển thị lỗi mới
                    Object.keys(errors).forEach(field => {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="text-danger">${errors[field][0]}</div>`);
                    });
                }
            },
            complete: function() {
                // Ẩn loading
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Reset form khi đóng modal
    $('#addAddressModal').on('hidden.bs.modal', function() {
        $('#addAddressForm')[0].reset();
        $('#ward-add').html('<option value="">Chọn Phường/Xã *</option>');
        $('.text-danger').remove();
        $('.is-invalid').removeClass('is-invalid');
    });
    
    // Xóa validation khi người dùng nhập
    $('#addAddressForm input, #addAddressForm select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.text-danger').remove();
    });
    
    // Validation form chính trước khi submit
    $('form[action="{{ route('checkout.placeOrder') }}"]').on('submit', function(e) {
        let isValid = true;
        
        // Kiểm tra các trường bắt buộc
        const requiredFields = [
            { name: 'first_name', label: 'Họ' },
            { name: 'last_name', label: 'Tên' },
            { name: 'email', label: 'Email' },
            { name: 'phone', label: 'Số điện thoại' },
            { name: 'address', label: 'Địa chỉ' }
        ];
        
        requiredFields.forEach(field => {
            const input = $(`[name="${field.name}"]`);
            const value = input.val().trim();
            
            input.removeClass('is-invalid');
            input.next('.text-danger').remove();
            
            if (!value) {
                input.addClass('is-invalid');
                input.after(`<div class="text-danger">Vui lòng nhập ${field.label}</div>`);
                isValid = false;
            }
        });
        
        // Kiểm tra email format
        const email = $('[name="email"]').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            $('[name="email"]').addClass('is-invalid');
            $('[name="email"]').after('<div class="text-danger">Định dạng email không hợp lệ</div>');
            isValid = false;
        }
        
        // Kiểm tra số điện thoại
        const phone = $('[name="phone"]').val();
        const phoneRegex = /^0[0-9]{9}$/;
        if (phone && !phoneRegex.test(phone)) {
            $('[name="phone"]').addClass('is-invalid');
            $('[name="phone"]').after('<div class="text-danger">Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0</div>');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            // Scroll đến lỗi đầu tiên
            const firstError = $('.is-invalid').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        } else {
            // Hiển thị loading cho nút đặt hàng
            const submitBtn = $('.place-order-btn');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý đơn hàng...');
        }
    });
    
    // Xóa validation khi người dùng nhập
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.text-danger').remove();
    });
});
</script>

@endsection