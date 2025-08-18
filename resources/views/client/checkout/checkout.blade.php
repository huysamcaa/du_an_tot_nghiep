@extends('client.layouts.app')

@section('content')
<style>
    .checkout-container {
        background-color: #f5f5f5;
        padding: 20px 0;
    }
    .checkout-header {
        background-color: #fff;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
    }
    .checkout-header h2 {
        font-size: 20px;
        font-weight: 500;
        color: #222;
        margin: 0;
    }
    .checkout-card {
        background-color: #fff;
        border-radius: 4px;
        margin-bottom: 15px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,.1);
    }
    .checkout-card-header {
        padding: 15px;
        border-bottom: 1px solid #f2f2f2;
        display: flex;
        align-items: center;
    }
    .checkout-card-header h3 {
        font-size: 16px;
        font-weight: 500;
        color: #222;
        margin: 0;
        flex-grow: 1;
    }
    .checkout-card-header .icon {
        color: #9ebbbd;
        margin-right: 10px;
        font-size: 18px;
    }
    .checkout-card-body {
        padding: 15px;
    }
    .address-item {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #f2f2f2;
    }
    .address-item:last-child {
        border-bottom: none;
    }
    .address-info {
        flex-grow: 1;
    }
    .address-name {
        font-weight: 500;
        margin-bottom: 5px;
    }
    .address-details {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .address-phone {
        color: #666;
        font-size: 14px;
    }
    .select-address {
        color: #9ebbbd;
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
    }
    .product-info {
        flex-grow: 1;
    }
    .product-name {
        font-size: 14px;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-variant {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    .product-price {
        color: #9ebbbd;
        font-weight: 500;
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
        color: #222;
        font-weight: 500;
    }
    .price-value.discount {
        color: #9ebbbd;
    }
    .price-value.total {
        color: #9ebbbd;
        font-size: 18px;
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
        font-weight: 500;
        margin-bottom: 5px;
    }
    .payment-desc {
        color: #666;
        font-size: 13px;
    }
    .payment-icon {
        width: 30px;
        margin-right: 10px;
    }
    .checkout-btn {
        background-color: #9ebbbd;
        color: #fff;
        border: none;
        width: 100%;
        padding: 15px;
        font-size: 16px;
        font-weight: 500;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
    }
    .checkout-btn:hover {
        opacity: 0.9;
    }
    .voucher-input {
        display: flex;
        margin-top: 10px;
    }
    .voucher-input input {
        flex-grow: 1;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 2px 0 0 2px;
        outline: none;
    }
    .voucher-input button {
        background-color: #9ebbbd;
        color: #fff;
        border: none;
        padding: 0 15px;
        border-radius: 0 2px 2px 0;
        cursor: pointer;
    }
    .add-address-btn {
        color: #9ebbbd;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
    }
    .note-input {
        width: 100%;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 2px;
        margin-top: 15px;
        resize: vertical;
        min-height: 60px;
    }
    .default-badge {
        background-color: #9ebbbd;
        color: white;
        font-size: 12px;
        padding: 2px 5px;
        border-radius: 2px;
        margin-left: 10px;
    }
    .address-select {
        width: 100%;
        border: none;
        padding: 0;
        color: #666;
        font-size: 14px;
        background: transparent;
    }
    .coupon-select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 2px;
    }
</style>

<section class="checkout-container">
    <div class="container">
        <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkoutForm">
            @csrf
            <!-- Các trường ẩn giữ nguyên logic cũ -->
            <input type="hidden" name="field1" id="hiddenField1" value="{{ auth()->user()->name ? explode(' ', auth()->user()->name)[0] : '' }}">
            <input type="hidden" name="field2" id="hiddenField2" value="{{ auth()->user()->name ? implode(' ', array_slice(explode(' ', auth()->user()->name), 1)) : '' }}">
            <input type="hidden" name="field4" value="{{ auth()->user()->email ?? '' }}">
            <input type="hidden" name="field5" id="hiddenField5" value="{{ $defaultAddress->phone_number ?? auth()->user()->phone_number ?? '' }}">
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
                                    <div class="address-name">
                                        {{ auth()->user()->name ?? '' }}
                                        @if($defaultAddress)
                                            <span class="default-badge">Mặc định</span>
                                        @endif
                                    </div>
                                    <div class="address-phone">(+84) {{ auth()->user()->phone_number ?? '' }}</div>
                                    <div class="address-details">
                                        <select name="address_id" class="address-select" id="addressSelect" required>
                                            @foreach($userAddresses as $address)
                                                <option value="{{ $address->id }}"
                                                    {{ ($defaultAddress && $defaultAddress->id == $address->id) ? 'selected' : '' }}
                                                    data-address="{{ $address->address }}"
                                                    data-phone="{{ $address->phone_number }}"
                                                    data-fullname="{{ $address->fullname }}">
                                                    {{ $address->address }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="select-address" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="fas fa-plus"></i> Thêm địa chỉ mới
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phần sản phẩm -->
                    <div class="checkout-card">
                        <div class="checkout-card-header">
                            <i class="fas fa-shopping-cart icon"></i>
                            <h3>Sản Phẩm</h3>
                        </div>
                        <div class="checkout-card-body">
                            @foreach($cartItems as $item)
                            <div class="product-item align-items-center">
                                <img src="{{ asset('storage/' . ($item->variant->thumbnail ?? $item->product->thumbnail)) }}"
                                     class="product-image rounded border"
                                     alt="Cart Item">
                                <div class="product-info">
                                    <div class="product-name fw-bold mb-1" title="{{ $item->product->name }}">
                                        {{ $item->product->name }}
                                    </div>
                                    @if($item->variant)
                                        <div class="product-variant mb-1">
                                            <span class="badge bg-light text-dark border">
                                                {{ $item->variant->variant_name ?? 'Chưa cấu hình thuộc tính' }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="product-price mb-1">
                                        {{ number_format(
                                            $item->variant
                                                ? ($item->variant->sale_price ?? $item->variant->price)
                                                : $item->product->price
                                        ) }}đ
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
                    
                    <!-- Phần voucher -->
                    <div class="checkout-card">
                        <div class="checkout-card-header">
                            <i class="fas fa-tag icon"></i>
                            <h3>Mã Giảm Giá</h3>
                        </div>
                        <div class="checkout-card-body">
                            <select id="coupon_code" name="coupon_code" class="coupon-select">
                                <option value="">-- Chọn mã giảm giá --</option>
                                @foreach($coupons as $coupon)
                                    <option
                                        value="{{ $coupon->code }}"
                                        data-discount-type="{{ $coupon->discount_type }}"
                                        data-discount-value="{{ $coupon->discount_value }}"
                                        data-max-discount="{{ $coupon->restriction->max_discount_value ?? '' }}"
                                    >
                                        {{ $coupon->code }} -
                                        @if($coupon->discount_type == 'percent')
                                            Giảm {{ $coupon->discount_value }}%
                                        @else
                                            Giảm {{ number_format($coupon->discount_value) }}₫
                                        @endif
                                    </option>
                                @endforeach
                            </select>
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
                                <input type="radio" value="4" name="paymentMethod" id="paymentMethod04" class="payment-radio" required>
                                <img src="https://th.bing.com/th/id/OIP.pn3RUm1xk1HiAxWIgC6CIwHaHa?w=161&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="VNPay" class="payment-icon">
                                <div class="payment-info">
                                    <label for="paymentMethod04" class="payment-name">VNPay</label>
                                    <div class="payment-desc">
                                        Thanh toán qua cổng thanh toán VNPay (ATM/VISA/MasterCard).
                                    </div>
                                </div>
                            </div>
                            <div class="payment-method">
                                <input type="radio" value="2" name="paymentMethod" id="paymentMethod02" class="payment-radio" checked>
                                <img src="https://th.bing.com/th/id/OIP.sdjsIUIEBcdxUtOdhD8iOAAAAA?w=162&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="COD" class="payment-icon">
                                <div class="payment-info">
                                    <label for="paymentMethod02" class="payment-name">Thanh toán khi nhận hàng</label>
                                    <div class="payment-desc">
                                        Thanh toán bằng tiền mặt khi giao hàng.
                                    </div>
                                </div>
                            </div>
                            <div class="payment-method">
                                <input type="radio" value="3" name="paymentMethod" id="paymentMethod03" class="payment-radio" required>
                                <img src="https://th.bing.com/th/id/OIP.-DhgkiQDEdoru7CJdZrwEAHaHa?w=169&h=180&c=7&r=0&o=7&pid=1.7&rm=3" alt="MoMo" class="payment-icon">
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
                            
                            <div class="price-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #ddd;">
                                <span class="price-label">Tổng cộng</span>
                                <span class="price-value total" id="final-total">{{ number_format($total + 30000) }}đ</span>
                            </div>
                            
                            <button type="submit" class="checkout-btn">
                                ĐẶT HÀNG
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
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" placeholder="Nhập họ và tên" value="{{ old('fullname') }}" required>
                            @error('fullname')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone_number" class="form-control" placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}" pattern="^0[0-9]{9}$" required>
                            @error('phone_number')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select id="province-add" name="province" class="form-select" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                @foreach($vnLocationsData as $province)
                                    <option value="{{ $province['Name'] }}" {{ old('province') == $province['Name'] ? 'selected' : '' }}>
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
                                @if(old('province') && $selectedProvince = collect($vnLocationsData)->firstWhere('Name', old('province')))
                                    @foreach(collect($selectedProvince['Districts'])->flatMap(fn($d) => $d['Wards']) as $ward)
                                        <option value="{{ $ward['Name'] }}" {{ old('ward') == $ward['Name'] ? 'selected' : '' }}>
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
                            <input type="text" name="address" id="addressInput-add" class="form-control" placeholder="Số nhà, tên đường..." value="{{ old('address') }}" required>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_default" id="is_default" value="1" class="form-check-input" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-danger">Lưu địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    const subtotal = {{ $total }};
    const shippingFee = 30000;
    let currentTotal = subtotal + shippingFee;

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
    });

    // Xử lý chọn coupon
    $('#coupon_code').on('change', function() {
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

        currentTotal = subtotal + shippingFee - discount;
        $('#final-total').text(Math.round(currentTotal).toLocaleString('vi-VN') + 'đ');
        $('#final-total').css('color', '#9ebbbd').animate({fontSize: '1.1em'}, 200).animate({fontSize: '1em'}, 200);
    });

    // Xử lý modal địa chỉ
    const vnLocationsData = @json($vnLocationsData);
    const provinceSelectAdd = document.getElementById("province-add");
    const wardSelectAdd = document.getElementById("ward-add");

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
                    alert('Vui lòng kiểm tra lại thông tin: ' + Object.values(errors).join('\n'));
                } else {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            }
        });
    });

    
});
</script>
@endsection