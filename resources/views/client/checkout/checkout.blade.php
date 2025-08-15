@extends('client.layouts.app')

@section('content')
<style>
    /* Reset và Căn chỉnh cơ bản */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8f9fa; /* Nền nhẹ nhàng hơn */
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px; /* Điều chỉnh margin để bù đắp padding của col */
    }

    .col-lg-12, .col-lg-8, .col-lg-4, .col-md-6, .col-12 {
        padding: 0 15px; /* Padding cho cột */
    }

    /* Page Banner Section */
    .pageBannerSection {
        background: #ECF5F4;
        padding: 60px 0; /* Tăng padding để tạo không gian thoáng đãng */
        text-align: center;
    }

    .pageBannerContent h2 {
        font-size: 48px; /* Kích thước font chữ vừa phải, hiện đại */
        color: #333; /* Màu chữ đen đậm hơn, chuyên nghiệp */
        font-weight: bold;
        margin-bottom: 10px;
    }

    .pageBannerPath a {
        color: #007bff; /* Màu xanh dương tiêu chuẩn */
        text-decoration: none;
        transition: color 0.3s ease-in-out;
    }

    .pageBannerPath a:hover {
        color: #0056b3;
    }

    .pageBannerPath span {
        color: #6c757d; /* Màu xám cho chữ không phải link */
    }

    /* Checkout Page Section */
    .checkoutPage {
        padding: 50px 0;
        background-color: #f8f9fa;
    }

    /* Checkout Card (chung cho cả form và summary) */
    .checkout-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); /* Bóng đổ mềm mại, rõ hơn */
        padding: 30px;
        margin-bottom: 30px;
    }

    .checkout-card h3 {
        font-size: 24px; /* Kích thước tiêu đề */
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
        border-bottom: 1px solid #eee; /* Đường kẻ dưới tiêu đề */
        padding-bottom: 15px;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 20px; /* Khoảng cách giữa các nhóm form */
    }

    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #555;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        color: #333;
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none; /* Reset default select styles */
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #fff;
        background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\'%3e%3cpath fill=\'none\' stroke=\'%23343a40\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2 5l6 6 6-6\'/%3e%3c/svg%3e'); /* Custom arrow for select */
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff; /* Màu xanh dương khi focus */
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        font-size: 0.875em;
        color: #dc3545;
        margin-top: 5px;
        display: block; /* Đảm bảo luôn hiển thị trên dòng mới */
    }

    textarea.form-control {
        min-height: 100px; /* Chiều cao tối thiểu cho textarea */
        resize: vertical; /* Cho phép thay đổi kích thước theo chiều dọc */
    }

    /* Buttons */
    .btn-outline-primary {
        border: 1px solid #007bff;
        color: #007bff;
        background-color: transparent;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out, border-color 0.3s ease-in-out;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }

    .btn-primary {
        background-color: #007bff; /* Màu xanh dương chính */
        color: #fff;
        padding: 14px 30px;
        border-radius: 6px;
        font-weight: 600;
        transition: background-color 0.3s ease-in-out;
        border: none;
        width: 100%; /* Nút full width */
        font-size: 18px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Order Summary & Coupon */
    .order-summary-card {
        /* Kế thừa từ .checkout-card */
    }

    .order-summary-card table {
        width: 100%;
        margin-bottom: 25px;
        border-collapse: collapse;
    }

    .order-summary-card table th,
    .order-summary-card table td {
        padding: 15px 0;
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    .order-summary-card table th {
        font-weight: normal;
        color: #6c757d;
    }

    .order-summary-card table td {
        color: #333;
        font-weight: 500;
        text-align: right;
    }

    .order-summary-card table tr:last-child th,
    .order-summary-card table tr:last-child td {
        border-bottom: none;
        font-size: 1.3em; /* Tổng tiền lớn hơn */
        font-weight: bold;
        color: #28a745; /* Màu xanh lá cho tổng tiền */
        padding-top: 20px;
    }

    #discount-amount {
        color: #dc3545 !important; /* Màu đỏ cho giảm giá */
    }

    /* Payment Methods */
    .payment-methods-list {
        list-style: none;
        padding: 0;
        margin-top: 30px;
    }

    .payment-methods-list li {
        margin-bottom: 15px;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 6px;
        display: flex; /* Dùng flexbox để căn chỉnh radio và label */
        align-items: flex-start; /* Căn trên đầu */
        transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
        cursor: pointer;
    }

    .payment-methods-list li:last-child {
        margin-bottom: 0;
    }

    .payment-methods-list li:hover {
        background-color: #f6f6f6;
        border-color: #ddd;
    }

    .payment-methods-list input[type="radio"] {
        margin-right: 15px;
        flex-shrink: 0; /* Không cho radio button co lại */
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 50%;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        outline: none;
        cursor: pointer;
        position: relative;
        top: 2px; /* Căn chỉnh nhẹ cho radio button */
    }

    .payment-methods-list input[type="radio"]:checked {
        border-color: #007bff;
        background-color: #fff;
    }

    .payment-methods-list input[type="radio"]:checked::before {
        content: '';
        display: block;
        width: 10px;
        height: 10px;
        background-color: #007bff;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .payment-methods-list label {
        font-weight: 600; /* In đậm nhãn phương thức thanh toán */
        color: #333;
        cursor: pointer;
        flex-grow: 1; /* Cho phép label chiếm không gian còn lại */
    }

    .paymentDesc {
        font-size: 0.9em;
        color: #777;
        margin-top: 5px;
        padding-left: 35px; /* Thụt lề để thẳng hàng với label */
        line-height: 1.5;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* Bóng đổ mạnh hơn cho modal */
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        background-color: #f7f7f7;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
    }

    .btn-close {
        font-size: 1rem;
        transition: opacity 0.3s ease-in-out;
    }

    .btn-close:hover {
        opacity: 0.8;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
    }

    .modal-footer .btn-secondary {
        color: #6c757d;
        background-color: #e9ecef;
        border-color: #e9ecef;
        border-radius: 6px;
        padding: 0.8rem 1.6rem;
        margin-left: 10px;
        transition: all 0.3s ease-in-out;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #dee2e6;
        border-color: #dee2e6;
    }

    .modal-footer .btn-danger { /* Đổi màu nút Hoàn thành trong modal */
        background-color: #28a745; /* Xanh lá */
        border-color: #28a745;
        padding: 0.8rem 1.6rem;
        border-radius: 6px;
        transition: background-color 0.3s ease-in-out;
    }

    .modal-footer .btn-danger:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    /* Alert messages */
    .alert {
        margin-bottom: 20px;
        padding: 15px 20px;
        border-radius: 6px;
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .alert .btn-close {
        margin-left: auto;
        padding: 0;
        border: none;
        background: transparent;
        font-size: 1.25rem;
        cursor: pointer;
    }

    /* Responsive adjustments */
    @media (min-width: 992px) {
        .checkout-layout {
            display: flex;
            gap: 30px; /* Khoảng cách giữa các cột chính */
            align-items: flex-start; /* Căn chỉnh các khối ở phía trên */
        }

        .checkout-form-col {
            flex: 2; /* Cột form chiếm 2 phần */
            min-width: 0; /* Reset min-width để flex hoạt động tốt */
        }

        .checkout-summary-col {
            flex: 1; /* Cột tóm tắt chiếm 1 phần */
            min-width: 0;
        }
    }

    @media (max-width: 991px) {
        .checkout-form-col,
        .checkout-summary-col {
            width: 100%; /* Trên tablet và di động, các cột xếp chồng */
            padding: 0; /* Xóa padding ngang thừa */
        }

        .checkout-summary-col {
            margin-top: 30px; /* Khoảng cách khi xếp chồng */
        }

        .pageBannerContent h2 {
            font-size: 36px;
        }
    }

    @media (max-width: 767px) {
        .checkout-card {
            padding: 20px; /* Giảm padding trên di động */
        }

        .checkout-card h3 {
            font-size: 20px;
        }

        .form-control, .form-select, .btn-primary {
            font-size: 15px;
            padding: 10px 12px;
        }
    }
</style>

<section class="pageBannerSection">
    <div class="container">
        <div class="pageBannerContent text-center">
            <h2>Thanh Toán</h2>
            <div class="pageBannerPath">
                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Thanh toán</span>
            </div>
        </div>
    </div>
</section>

<section class="checkoutPage">
    <div class="container">
        <form action="{{ route('checkout.placeOrder') }}" method="POST">
            @csrf
            <div class="checkout-layout"> {{-- Sử dụng lớp mới cho layout ngang --}}
                <div class="checkout-form-col"> {{-- Cột dành cho form địa chỉ --}}
                    <div class="checkout-card">
                        <h3>Địa chỉ thanh toán</h3>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="billing_last_name">Họ *</label>
                                <input type="text" name="field1" id="billing_last_name" class="form-control" placeholder="Họ" required>
                                @error('field1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="billing_first_name">Tên *</label>
                                <input type="text" name="field2" id="billing_first_name" class="form-control" placeholder="Tên" required>
                                @error('field2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="billing_email">Địa chỉ Email *</label>
                                <input type="email" name="field4" id="billing_email" class="form-control" placeholder="Địa chỉ email" value="{{ auth()->user()->email ?? '' }}" required>
                                @error('field4')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="billing_phone">Số điện thoại *</label>
                                <input type="text" name="field5" id="billing_phone" class="form-control" placeholder="Số điện thoại" value="{{ auth()->user()->phone_number ?? '' }}" required>
                                @error('field5')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="country_select">Quốc gia *</label>
                                <select name="field6" id="country_select" class="form-select">
                                    <option value="VN" selected>Việt Nam</option>
                                </select>
                                @error('field6')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="addressSelect">Chọn địa chỉ đã lưu *</label>
                                <select name="field7" class="form-select address-select" id="addressSelect" required>
                                    <option value="">Chọn địa chỉ *</option>
                                    @foreach($userAddresses as $address)
                                        <option value="{{ $address->address }}"
                                            {{ old('field7', $defaultAddress->address ?? '') == $address->address ? 'selected' : '' }}
                                            data-phone="{{ $address->phone_number }}"
                                            data-fullname="{{ $address->fullname }}">
                                            {{ $address->address }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('field7')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12 form-group">
                                <label for="order_notes">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea name="field14" id="order_notes" class="form-control" placeholder="Ghi chú về đơn hàng của bạn, ví dụ: lưu ý đặc biệt khi giao hàng."></textarea>
                                @error('field14')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <span>+ Thêm địa chỉ mới</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkout-summary-col"> {{-- Cột dành cho tổng quan đơn hàng và thanh toán --}}
                    <div class="checkout-card order-summary-card">
                        <h3>Mã giảm giá</h3>
                        <div class="form-group">
                            <label for="coupon_code_select">Chọn mã giảm giá</label>
                            <select id="coupon_code_select" name="coupon_code" class="form-select">
                                <option value="">-- Chọn mã giảm giá --</option>
                                @foreach($coupons as $coupon)
                                    <option
                                        value="{{ $coupon->code }}"
                                        data-discount-type="{{ $coupon->discount_type }}"
                                        data-discount-value="{{ $coupon->discount_value }}"
                                        data-max-discount="{{ $coupon->restriction->max_discount_value ?? 0 }}"
                                    >
                                        {{ $coupon->code }} -
                                        @if($coupon->discount_type == 'percent')
                                            Giảm {{ $coupon->discount_value }}%
                                        @else
                                            Giảm {{ number_format($coupon->discount_value) }}₫
                                        @endif
                                        @isset($coupon->end_date)
                                            (HSD: {{ $coupon->end_date->format('d/m/Y') }})
                                        @else
                                            (HSD: Không xác định)
                                        @endisset
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="checkout-card order-summary-card mt-4"> {{-- Thẻ tóm tắt đơn hàng --}}
                        <h3>Đơn hàng của bạn</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                                    <td>
                                        <a href="javascript:void(0);">
                                            {{ $item->product->name }}
                                            @if($item->variant)
                                                    {{ $item->variant->sku }}
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $price = $item->variant ?
                                                        ($item->variant->sale_price ?? $item->variant->price) :
                                                        $item->product->price;
                                            $itemTotal = $price * $item->quantity;
                                        @endphp
                                        <ins>{{ number_format($itemTotal) }}đ</ins>
                                    </td>
                                </tr>
                                @endforeach

                                <tr>
                                    <th>Tổng tiền hàng</th>
                                    <td>
                                        <ins id="subtotal">{{ number_format($total) }} đ</ins>
                                    </td>
                                </tr>

                                <tr class="shippingRow">
                                    <th>Phí vận chuyển</th>
                                    <td>
                                        <ins id="shipping-fee">30,000đ</ins>
                                    </td>
                                </tr>

                                <tr id="discount-row" style="display: none;">
                                    <th>Giảm giá</th>
                                    <td>
                                        <ins id="discount-amount">-0đ</ins>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tổng thanh toán</th>
                                    <td>
                                        <ins id="final-total">{{ number_format($total + 30000) }}đ</ins>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="mt-4">Phương thức thanh toán</h3> {{-- Tiêu đề cho phương thức thanh toán --}}
                        <ul class="payment-methods-list">
                            <li>
                                <input type="radio" value="4" name="paymentMethod" id="paymentMethod04" required>
                                <label for="paymentMethod04">VNPay</label>
                                <div class="paymentDesc">
                                    Thanh toán qua cổng thanh toán VNPay (ATM/VISA/MasterCard).
                                </div>
                            </li>
                            <li>
                                <input type="radio" value="2" name="paymentMethod" id="paymentMethod02" checked>
                                <label for="paymentMethod02">Thanh toán khi nhận hàng (COD)</label>
                                <div class="paymentDesc">
                                    Thanh toán bằng tiền mặt khi giao hàng.
                                </div>
                            </li>
                            <li>
                                <input type="radio" value="3" name="paymentMethod" id="paymentMethod03" required>
                                <label for="paymentMethod03">MoMo</label>
                                <div class="paymentDesc">
                                    Thanh toán qua tài khoản MoMo.
                                </div>
                            </li>
                        </ul>
                        <button type="submit" class="btn btn-primary mt-4"><span>Đặt hàng</span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Modal Thêm Địa chỉ --}}
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAddressForm" action="{{ route('user.addresses.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label for="new_fullname">Họ và tên *</label>
                            <input type="text" name="fullname" id="new_fullname" class="form-control" placeholder="Họ và tên" value="{{ old('fullname') }}" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="new_phone">Số điện thoại *</label>
                            <input type="tel" name="phone_number" id="new_phone" class="form-control" placeholder="Số điện thoại" value="{{ old('phone_number') }}" pattern="^0[0-9]{9}$" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="province-add">Tỉnh/Thành phố *</label>
                            <select id="province-add" name="province" class="form-select" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                @foreach($vnLocationsData as $province)
                                    <option value="{{ $province['Name'] }}" {{ old('province') == $province['Name'] ? 'selected' : '' }}>
                                        {{ $province['Name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="ward-add">Phường/Xã *</label>
                            <select id="ward-add" name="ward" class="form-select" required>
                                <option value="">Chọn Phường/Xã</option>
                                @if(old('province') && $selectedProvince = collect($vnLocationsData)->firstWhere('Name', old('province')))
                                    @foreach(collect($selectedProvince['Districts'])->flatMap(fn($d) => $d['Wards']) as $ward)
                                        <option value="{{ $ward['Name'] }}" {{ old('ward') == $ward['Name'] ? 'selected' : '' }}>
                                            {{ $ward['Name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 form-group">
                            <label for="addressInput-add">Địa chỉ cụ thể (Số nhà, tên đường...) *</label>
                            <input type="text" name="address" id="addressInput-add" class="form-control" placeholder="Địa chỉ cụ thể (Số nhà, tên đường...)" value="{{ old('address') }}" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12 form-group">
                            <div class="form-check">
                                <input type="checkbox" name="id_default" id="id_default_new" value="1" class="form-check-input" {{ old('id_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="id_default_new">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-danger" id="saveNewAddress">
                            <span id="saveButtonText">Hoàn thành</span>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;" id="saveButtonSpinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const vnLocationsData = @json($vnLocationsData);

        // Logic cho modal Thêm Địa chỉ
        const provinceSelectAdd = document.getElementById("province-add");
        const wardSelectAdd = document.getElementById("ward-add");

        if (provinceSelectAdd && wardSelectAdd) {
            provinceSelectAdd.addEventListener("change", function () {
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

        // Logic cho các modal Sửa Địa chỉ (nếu có) - Giữ nguyên nếu bạn có các modal chỉnh sửa khác
        document.querySelectorAll(".province-edit").forEach(provinceSelect => {
            const id = provinceSelect.dataset.id;
            const wardSelect = document.querySelector(`.ward-edit[data-id='${id}']`);

            provinceSelect.addEventListener("change", function () {
                const selectedProvinceName = this.value;
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                if (selectedProvinceName) {
                    const selectedProvince = vnLocationsData.find(p => p.Name === selectedProvinceName);
                    if (selectedProvince) {
                        const allWards = selectedProvince.Districts.flatMap(d => d.Wards);
                        allWards.forEach(ward => {
                            wardSelect.add(new Option(ward.Name, ward.Name));
                        });
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        const subtotal = {{ $total }};
        const shippingFee = 30000;

        // Xóa dữ liệu form modal
        function clearAddressFormModal() {
            $('#addAddressForm input[type="text"], #addAddressForm input[type="tel"]').val('');
            $('#addAddressForm select').val('');
            $('#addAddressForm input[type="checkbox"]').prop('checked', false);
            $('#addAddressForm .form-control').removeClass('is-invalid');
            $('#addAddressForm .invalid-feedback').text('');
        }

        // Đóng modal và reset form khi đóng
        $('#addAddressModal').on('hidden.bs.modal', function () {
            clearAddressFormModal();
        });

        // Validation for new address form in modal
        $('#addAddressForm').on('submit', function(e) {
            e.preventDefault(); // Ngăn chặn submit form mặc định
            let isValid = true;

            const fields = [
                { id: 'new_fullname', message: 'Vui lòng nhập họ tên.' },
                { id: 'new_phone', message: 'Vui lòng nhập số điện thoại.', pattern: /^0[0-9]{9}$/, patternMessage: 'Số điện thoại phải bắt đầu bằng 0 và gồm đúng 10 chữ số.' },
                { id: 'province-add', message: 'Vui lòng chọn Tỉnh/Thành phố.' },
                { id: 'ward-add', message: 'Vui lòng chọn Phường/Xã.' },
                { id: 'addressInput-add', message: 'Vui lòng nhập địa chỉ cụ thể.' }
            ];

            fields.forEach(field => {
                const input = $('#' + field.id);
                const value = input.val().trim();
                const feedback = input.siblings('.invalid-feedback');

                input.removeClass('is-invalid');
                feedback.text('');

                if (!value) {
                    input.addClass('is-invalid');
                    feedback.text(field.message);
                    isValid = false;
                } else if (field.pattern && !field.pattern.test(value)) {
                    input.addClass('is-invalid');
                    feedback.text(field.patternMessage);
                    isValid = false;
                }
            });

            if (!isValid) {
                return; // Dừng nếu có lỗi validation
            }

            // Nếu form hợp lệ, tiến hành gửi AJAX
            const button = $('#saveNewAddress');
            const buttonText = $('#saveButtonText');
            const spinner = $('#saveButtonSpinner');

            button.prop('disabled', true);
            buttonText.hide();
            spinner.show();

            const formData = $(this).serialize(); // Lấy tất cả dữ liệu từ form

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Cập nhật select địa chỉ chính
                    const newAddressOption = response.newAddress; // Server trả về địa chỉ mới
                    const newOption = new Option(newAddressOption.address_full, newAddressOption.address_full, false, true);
                    $(newOption).attr({
                        'data-phone': newAddressOption.phone_number,
                        'data-fullname': newAddressOption.fullname
                    });
                    $('#addressSelect').append(newOption);

                    // Cập nhật thông tin form chính với địa chỉ mới được chọn
                    const nameParts = newAddressOption.fullname.split(' ');
                    $('input[name="field1"]').val(nameParts[0] || '');
                    $('input[name="field2"]').val(nameParts.slice(1).join(' ') || '');
                    $('input[name="field5"]').val(newAddressOption.phone_number);

                    $('#addAddressModal').modal('hide'); // Đóng modal
                    showSuccessMessage('Thêm địa chỉ thành công!');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(field => {
                            const inputElement = $(`#addAddressForm [name="${field}"]`);
                            const feedbackElement = inputElement.siblings('.invalid-feedback');
                            inputElement.addClass('is-invalid');
                            feedbackElement.text(errors[field][0]);
                        });
                    } else {
                        showErrorMessage('Có lỗi xảy ra khi thêm địa chỉ. Vui lòng thử lại!');
                    }
                },
                complete: function() {
                    button.prop('disabled', false);
                    buttonText.show();
                    spinner.hide();
                }
            });
        });

        // Xóa validation khi người dùng nhập trong modal
        $('#addAddressForm input, #addAddressForm select').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        });


        // Hiển thị thông báo (Bạn có thể đặt ở đâu đó trên trang chính, ví dụ sau <section class="checkoutPage">)
        function showSuccessMessage(message) {
            const alertHtml = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            $('.checkoutPage').prepend(alertHtml); // Chèn vào đầu section checkoutPage
            setTimeout(() => $('.alert').alert('close'), 5000); // Tự động đóng sau 5 giây
        }

        function showErrorMessage(message) {
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            $('.checkoutPage').prepend(alertHtml);
            setTimeout(() => $('.alert').alert('close'), 5000);
        }

        // Cập nhật thông tin form chính khi chọn địa chỉ đã có
        $('.address-select').change(function() {
            const selectedOption = $(this).find('option:selected');
            const fullname = selectedOption.data('fullname') || '';
            const phone = selectedOption.data('phone') || '';
            
            // Tách họ và tên
            const nameParts = fullname.split(' ');
            const lastName = nameParts.length > 0 ? nameParts[0] : '';
            const firstName = nameParts.length > 1 ? nameParts.slice(1).join(' ') : '';

            $('input[name="field1"]').val(lastName);
            $('input[name="field2"]').val(firstName);
            $('input[name="field5"]').val(phone);
        });

        // Logic cho coupon
        $('#coupon_code_select').on('change', function() {
            let discount = 0;
            const selectedCoupon = $(this).find('option:selected');
            const couponValue = selectedCoupon.val();

            if (couponValue && couponValue !== '') {
                const discountType = selectedCoupon.data('discount-type');
                const discountValue = parseFloat(selectedCoupon.data('discount-value'));
                const maxDiscount = parseFloat(selectedCoupon.data('max-discount')) || 0;

                if (discountType === 'percent') {
                    discount = (subtotal * discountValue) / 100;
                    if (maxDiscount > 0 && discount > maxDiscount) {
                        discount = maxDiscount;
                    }
                } else if (discountType === 'fixed') {
                    discount = discountValue;
                }

                if (discount > subtotal) {
                    discount = subtotal;
                }

                $('#discount-row').show();
                $('#discount-amount').text('-' + Math.round(discount).toLocaleString('vi-VN') + 'đ');

            } else {
                $('#discount-row').hide();
                discount = 0;
            }

            const finalTotal = subtotal + shippingFee - discount;
            $('#final-total').text(Math.round(finalTotal).toLocaleString('vi-VN') + 'đ');

            // Hiệu ứng màu xanh khi tổng tiền thay đổi
            $('#final-total').css('color', '#28a745').animate({fontSize: '1.1em'}, 200).animate({fontSize: '1em'}, 200);
            setTimeout(function() {
                $('#final-total').css('color', '');
            }, 1000);
        });

        // Cập nhật tổng tiền ban đầu khi tải trang
        const initialTotal = subtotal + shippingFee;
        $('#final-total').text(initialTotal.toLocaleString('vi-VN') + 'đ');

        // Kích hoạt thay đổi địa chỉ ban đầu nếu có địa chỉ mặc định
        if ($('#addressSelect').val() !== '') {
            $('#addressSelect').trigger('change');
        }
    });
</script>
@endsection