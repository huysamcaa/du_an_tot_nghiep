@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Thanh Toán</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Thanh toán</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkoutPage">
    <div class="container">
        <form action="{{ route('checkout.placeOrder') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="checkoutForm">
                        <h3>Địa chỉ thanh toán</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="field1" placeholder="Họ *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field2" placeholder="Tên *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="field4" placeholder="Địa chỉ email *" value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field5" placeholder="Số điện thoại *" value="{{ auth()->user()->phone_number ?? '' }}" required>
                            </div>
                            <div class="col-lg-12">
                                <select name="field6" style="display: none;">
                                    <option value="">Chọn quốc gia</option>
                                    <option value="VN" selected>Việt Nam</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-12">
                                <select name="field7" class="form-control address-select" id="addressSelect" required>
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
                            </div>

                            <div class="col-lg-12">
                                <div class="shippingAddress"></div>
                            </div>
                            <div class="col-lg-12">
                                <textarea name="field14" placeholder="Ghi chú đơn hàng"></textarea>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="showAddressForm" class="placeOrderBTN ulinaBTN">
                                    <span>+ Thêm địa chỉ mới</span>
                                </button>
                            </div>
                        </div>

                        <!-- Form thêm địa chỉ mới (ẩn ban đầu) -->
                        <div id="newAddressForm" class="mt-4" style="display: none;">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Thêm địa chỉ mới</h5>
                                    <button type="button" id="hideAddressForm" class="btn btn-sm btn-secondary">×</button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="new_fullname">Họ tên *</label>
                                                <input type="text" id="new_fullname" name="new_fullname" class="form-control" placeholder="Nhập họ tên">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="new_phone">Số điện thoại *</label>
                                                <input type="text" id="new_phone" name="new_phone" class="form-control" placeholder="09xxxxxxxx hoặc 03xxxxxxxx">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="new_area">Khu vực *</label>
                                                <input type="text" id="new_area" name="new_area" class="form-control" placeholder="Tỉnh/Thành phố, Quận/Huyện, Phường/Xã">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="new_address">Địa chỉ cụ thể *</label>
                                                <input type="text" id="new_address" name="new_address" class="form-control" placeholder="Số nhà, tên đường">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="new_id_default" name="new_id_default">
                                                <label class="form-check-label" for="new_id_default">
                                                    Đặt làm địa chỉ mặc định
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="button" id="saveNewAddress" class="btn btn-primary">
                                                <span id="saveButtonText">Lưu địa chỉ</span>
                                                <span id="saveButtonSpinner" class="spinner-border spinner-border-sm" style="display: none;"></span>
                                            </button>
                                            <button type="button" id="cancelAddressForm" class="btn btn-secondary ms-2">Hủy</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="shippingCoupons">
                        <h3>Mã giảm giá</h3>
                        <select id="coupon_code" name="coupon_code" class="form-control">
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
                                    @isset($coupon->end_date)
                                        (HSD: {{ $coupon->end_date->format('d/m/Y') }})
                                    @else
                                        (HSD: Không xác định)
                                    @endisset
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="orderReviewWrap">
                        <h3>Đơn hàng của bạn</h3>
                        <div class="orderReview">
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
                                        <div class="pi01Price">
                                            @php
                                                $price = $item->variant ?
                                                        ($item->variant->sale_price ?? $item->variant->price) :
                                                        $item->product->price;
                                                $itemTotal = $price * $item->quantity;
                                            @endphp
                                            <ins>{{ number_format($itemTotal) }}đ</ins>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                <tr>
                                    <th>Tổng tiền hàng</th>
                                    <td>
                                        <div class="pi01Price">
                                            <ins id="subtotal">{{ number_format($total) }} đ</ins>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="shippingRow">
                                    <th>Phí vận chuyển</th>
                                    <td>
                                        <div class="pi01Price">
                                            <ins id="shipping-fee">30,000đ</ins>
                                        </div>
                                    </td>
                                </tr>

                                <tr id="discount-row" style="display: none;">
                                    <th>Giảm giá</th>
                                    <td>
                                        <div class="pi01Price">
                                            <ins id="discount-amount" style="color: #dc3545;">-0đ</ins>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tổng thanh toán</th>
                                    <td>
                                        <div class="pi01Price">
                                            <ins id="final-total">{{ number_format($total + 30000) }}đ</ins>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                            <ul class="wc_payment_methods">
                                <li>
                                    <input type="radio" value="4" name="paymentMethod" id="paymentMethod04" required>
                                    <label for="paymentMethod04">VNPay</label>
                                    <div class="paymentDesc">
                                        Thanh toán qua cổng thanh toán VNPay (ATM/VISA/MasterCard).
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" value="2" name="paymentMethod" id="paymentMethod02" checked>
                                    <label for="paymentMethod02">Thanh toán khi nhận hàng</label>
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
                            <button type="submit" class="placeOrderBTN ulinaBTN"><span>Đặt hàng</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    const subtotal = {{ $total }};
    const shippingFee = 30000;
    
    // Hiển thị/ẩn form thêm địa chỉ
    $('#showAddressForm').on('click', function() {
        $('#newAddressForm').slideDown(300);
        $(this).hide();
    });
    
    $('#hideAddressForm, #cancelAddressForm').on('click', function() {
        $('#newAddressForm').slideUp(300);
        $('#showAddressForm').show();
        clearAddressForm();
    });
    
    // Xóa dữ liệu form
    function clearAddressForm() {
        $('#newAddressForm input').val('').removeClass('is-invalid');
        $('#newAddressForm .invalid-feedback').text('');
        $('#new_id_default').prop('checked', false);
    }
    
    // Validation
    function validateAddressForm() {
        let isValid = true;
        const fields = [
            { id: 'new_fullname', message: 'Vui lòng nhập họ tên' },
            { id: 'new_phone', message: 'Vui lòng nhập số điện thoại', pattern: /^(09|03)[0-9]{8}$/, patternMessage: 'Số điện thoại phải bắt đầu bằng 09 hoặc 03 và gồm đúng 10 chữ số' },
            { id: 'new_area', message: 'Vui lòng nhập khu vực' },
            { id: 'new_address', message: 'Vui lòng nhập địa chỉ cụ thể' }
        ];
        
        fields.forEach(field => {
            const input = $('#' + field.id);
            const value = input.val().trim();
            const feedback = input.siblings('.invalid-feedback');
            
            if (!value) {
                input.addClass('is-invalid');
                feedback.text(field.message);
                isValid = false;
            } else if (field.pattern && !field.pattern.test(value)) {
                input.addClass('is-invalid');
                feedback.text(field.patternMessage);
                isValid = false;
            } else {
                input.removeClass('is-invalid');
                feedback.text('');
            }
        });
        
        return isValid;
    }
    
    // Lưu địa chỉ mới
    $('#saveNewAddress').on('click', function() {
        if (!validateAddressForm()) {
            return;
        }
        
        const button = $(this);
        const buttonText = $('#saveButtonText');
        const spinner = $('#saveButtonSpinner');
        
        // Hiển thị loading
        button.prop('disabled', true);
        buttonText.hide();
        spinner.show();
        
        const formData = {
            fullname: $('#new_fullname').val().trim(),
            phone_number: $('#new_phone').val().trim(),
            area: $('#new_area').val().trim(),
            address: $('#new_address').val().trim(),
            id_default: $('#new_id_default').is(':checked') ? 1 : 0,
            _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
        };
        
        $.ajax({
            url: "{{ route('user.addresses.store') }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                // Tạo địa chỉ đầy đủ
                const fullAddress = formData.address + ', ' + formData.area;
                
                // Thêm option mới vào select
                const newOption = new Option(fullAddress, fullAddress, false, true);
                $(newOption).attr({
                    'data-phone': formData.phone_number,
                    'data-fullname': formData.fullname
                });
                $('#addressSelect').append(newOption);
                
                // Cập nhật thông tin form chính
                const nameParts = formData.fullname.split(' ');
                $('input[name="field1"]').val(nameParts[0] || '');
                $('input[name="field2"]').val(nameParts.slice(1).join(' ') || '');
                $('input[name="field5"]').val(formData.phone_number);
                
                // Ẩn form và hiện thông báo thành công
                $('#newAddressForm').slideUp(300);
                $('#showAddressForm').show();
                
                // Hiển thị thông báo thành công
                showSuccessMessage('Thêm địa chỉ thành công!');
                
                clearAddressForm();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        const input = $('#new_' + field);
                        const feedback = input.siblings('.invalid-feedback');
                        input.addClass('is-invalid');
                        feedback.text(errors[field][0]);
                    });
                } else {
                    showErrorMessage('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            },
            complete: function() {
                // Ẩn loading
                button.prop('disabled', false);
                buttonText.show();
                spinner.hide();
            }
        });
    });
    
    // Hiển thị thông báo
    function showSuccessMessage(message) {
        const alert = $(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        $('#newAddressForm').before(alert);
        setTimeout(() => alert.fadeOut(), 3000);
    }
    
    function showErrorMessage(message) {
        const alert = $(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        $('#newAddressForm').before(alert);
        setTimeout(() => alert.fadeOut(), 3000);
    }
    
    // Xóa validation khi người dùng nhập
    $('#newAddressForm input').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
    
    // Code cũ cho coupon và address selection
    $('.address-select').change(function() {
        const selectedOption = $(this).find('option:selected');
        $('input[name="field5"]').val(selectedOption.data('phone') || '');
        const fullname = selectedOption.data('fullname') || '';
        const nameParts = fullname.split(' ');
        $('input[name="field1"]').val(nameParts[0] || '');
        $('input[name="field2"]').val(nameParts.slice(1).join(' ') || '');
    });

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
        
        const finalTotal = subtotal + shippingFee - discount;
        $('#final-total').text(Math.round(finalTotal).toLocaleString('vi-VN') + 'đ');
        
        $('#final-total').css('color', '#28a745').animate({fontSize: '1.1em'}, 200).animate({fontSize: '1em'}, 200);
        setTimeout(function() {
            $('#final-total').css('color', '');
        }, 1000);
    });
    
    const initialTotal = subtotal + shippingFee;
    $('#final-total').text(initialTotal.toLocaleString('vi-VN') + 'đ');
});
</script>

<style>
#newAddressForm .card {
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#newAddressForm .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #ddd;
    padding: 1rem;
}

#newAddressForm .form-group label {
    font-weight: 500;
    color: #333;
    margin-bottom: 5px;
}

#newAddressForm .form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    transition: border-color 0.15s ease-in-out;
}

#newAddressForm .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

#newAddressForm .form-control.is-invalid {
    border-color: #dc3545;
}

#newAddressForm .invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.alert {
    margin-bottom: 1rem;
    padding: 0.75rem 1.25rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>
@endsection