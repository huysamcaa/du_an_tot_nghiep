@extends('client.layouts.app')

@section('content')
<style>
    /* Dark Green Theme */
    .pageBannerSection {
        background: #1a5d1a; /* Dark green background */
        padding: 10px 0;
    }
    .pageBannerContent h2 {
        font-size: 72px;
        color: #ffffff; /* White text */
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #a8df65; /* Light green link */
        text-decoration: none;
    }
    .pageBannerPath span {
        color: #ffffff; /* White text */
    }
    
    /* Green Buttons */
    .ulinaBTN, .placeOrderBTN {
        background-color: #2e8b57; /* Dark green button */
        color: white;
        border: none;
        transition: all 0.3s;
    }
    .ulinaBTN:hover, .placeOrderBTN:hover {
        background-color: #3cb371; /* Lighter green on hover */
        color: white;
    }
    
    /* Coupon Button */
    .coupon-button {
        background-color: #2e8b57;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .coupon-button:hover {
        background-color: #3cb371;
    }
    
    /* Coupon Modal */
    .coupon-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    .coupon-modal-content {
        background-color: #f8f9fa;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
    }
    .close-coupon-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close-coupon-modal:hover {
        color: black;
    }
    
    /* Coupon Items */
    .coupon-item {
        display: flex;
        align-items: center;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px dashed #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .coupon-item:hover {
        border-color: #2e8b57;
    }
    .coupon-item.selected {
        border: 1px solid #2e8b57;
        background-color: #e8f5e9;
    }
    .coupon-left {
        position: relative;
        padding: 0 15px;
        color: #2e8b57;
        font-weight: bold;
        text-align: center;
        border-right: 1px dashed #ddd;
    }
    .coupon-left:before, .coupon-left:after {
        content: "";
        position: absolute;
        right: -6px;
        width: 10px;
        height: 10px;
        background-color: #f8f9fa;
        border-radius: 50%;
        border: 1px dashed #ddd;
    }
    .coupon-left:before {
        top: -10px;
    }
    .coupon-left:after {
        bottom: -10px;
    }
    .coupon-right {
        padding-left: 15px;
    }
    .coupon-code {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .coupon-desc {
        font-size: 12px;
        color: #666;
    }
    .coupon-expiry {
        font-size: 11px;
        color: #999;
        margin-top: 5px;
    }
    .no-coupon {
        color: #999;
        font-style: italic;
        padding: 15px 0;
    }
    
    /* Form styling */
    input, select, textarea {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
        width: 100%;
    }
    input:focus, select:focus, textarea:focus {
        border-color: #2e8b57;
        outline: none;
        box-shadow: 0 0 0 2px rgba(46, 139, 87, 0.2);
    }
    
    /* Payment methods */
    .wc_payment_methods li {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .wc_payment_methods li:hover {
        border-color: #2e8b57;
    }
    .paymentDesc {
        font-size: 13px;
        color: #666;
        margin-top: 5px;
    }
</style>

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
                    <!-- Coupon Button -->
                    <button type="button" class="coupon-button" id="showCouponModal">
                        <i class="fas fa-tag"></i> Chọn mã giảm giá
                    </button>
                    
                    <!-- Selected Coupon Display -->
                    <div id="selectedCouponDisplay" style="display: none; margin-bottom: 15px; padding: 10px; background-color: #e8f5e9; border-radius: 4px;">
                        <strong>Mã đã chọn: </strong>
                        <span id="selectedCouponCode"></span>
                        <button type="button" id="removeCoupon" style="background: none; border: none; color: #dc3545; margin-left: 10px; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Coupon Modal -->
                    <div id="couponModal" class="coupon-modal">
                        <div class="coupon-modal-content">
                            <span class="close-coupon-modal">&times;</span>
                            <h3>Chọn mã giảm giá</h3>
                            <div class="coupon-container">
                                @if(count($coupons) > 0)
                                    @foreach($coupons as $coupon)
                                        <div class="coupon-item" 
                                             data-code="{{ $coupon->code }}"
                                             data-discount-type="{{ $coupon->discount_type }}"
                                             data-discount-value="{{ $coupon->discount_value }}"
                                             data-max-discount="{{ $coupon->restriction->max_discount_value ?? '' }}">
                                            <div class="coupon-left">
                                                @if($coupon->discount_type == 'percent')
                                                    {{ $coupon->discount_value }}%
                                                @else
                                                    {{ number_format($coupon->discount_value) }}₫
                                                @endif
                                            </div>
                                            <div class="coupon-right">
                                                <div class="coupon-code">{{ $coupon->code }}</div>
                                                <div class="coupon-desc">
                                                    @if($coupon->discount_type == 'percent')
                                                        Giảm {{ $coupon->discount_value }}% tối đa 
                                                        @if(isset($coupon->restriction->max_discount_value))
                                                            {{ number_format($coupon->restriction->max_discount_value) }}đ
                                                        @else
                                                            không giới hạn
                                                        @endif
                                                    @else
                                                        Giảm {{ number_format($coupon->discount_value) }}đ
                                                    @endif
                                                </div>
                                                <div class="coupon-expiry">
                                                    HSD: @isset($coupon->end_date))
                                                        {{ $coupon->end_date->format('d/m/Y') }}
                                                    @else
                                                        Không xác định
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-coupon">Không có mã giảm giá khả dụng</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="selected_coupon_code" name="coupon_code" value="">

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
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    const subtotal = {{ $total }};
    const shippingFee = 30000;
    
    // Coupon Modal Functionality
    const couponModal = document.getElementById("couponModal");
    const showCouponBtn = document.getElementById("showCouponModal");
    const closeCouponModal = document.getElementsByClassName("close-coupon-modal")[0];
    
    showCouponBtn.onclick = function() {
        couponModal.style.display = "block";
    }
    
    closeCouponModal.onclick = function() {
        couponModal.style.display = "none";
    }
    
    window.onclick = function(event) {
        if (event.target == couponModal) {
            couponModal.style.display = "none";
        }
    }
    
    // Coupon Selection
    $('.coupon-item').on('click', function() {
        const coupon = $(this);
        
        // Nếu coupon đã được chọn, bỏ chọn
        if (coupon.hasClass('selected')) {
            coupon.removeClass('selected');
            $('#selected_coupon_code').val('');
            $('#selectedCouponDisplay').hide();
            calculateFinalTotal(null);
            return;
        }
        
        // Bỏ chọn tất cả coupon khác
        $('.coupon-item').removeClass('selected');
        
        // Chọn coupon này
        coupon.addClass('selected');
        
        // Lưu mã coupon vào hidden input
        $('#selected_coupon_code').val(coupon.data('code'));
        
        // Hiển thị coupon đã chọn
        $('#selectedCouponCode').text(coupon.data('code'));
        $('#selectedCouponDisplay').show();
        
        // Đóng modal
        couponModal.style.display = "none";
        
        // Tính toán tổng tiền
        calculateFinalTotal({
            code: coupon.data('code'),
            discount_type: coupon.data('discount-type'),
            discount_value: parseFloat(coupon.data('discount-value')),
            max_discount: parseFloat(coupon.data('max-discount')) || 0
        });
    });
    
    // Xóa coupon đã chọn
    $('#removeCoupon').on('click', function() {
        $('.coupon-item').removeClass('selected');
        $('#selected_coupon_code').val('');
        $('#selectedCouponDisplay').hide();
        calculateFinalTotal(null);
    });
    
    // Tính toán tổng tiền cuối cùng
    function calculateFinalTotal(coupon) {
        let discount = 0;
        
        if (coupon) {
            if (coupon.discount_type === 'percent') {
                discount = (subtotal * coupon.discount_value) / 100;
                if (coupon.max_discount > 0 && discount > coupon.max_discount) {
                    discount = coupon.max_discount;
                }
            } else if (coupon.discount_type === 'fixed') {
                discount = coupon.discount_value;
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
        
        $('#final-total').css('color', '#2e8b57').animate({fontSize: '1.1em'}, 200).animate({fontSize: '1em'}, 200);
        setTimeout(function() {
            $('#final-total').css('color', '');
        }, 1000);
    }
    
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
    
    // Code cũ cho address selection
    $('.address-select').change(function() {
        const selectedOption = $(this).find('option:selected');
        $('input[name="field5"]').val(selectedOption.data('phone') || '');
        const fullname = selectedOption.data('fullname') || '';
        const nameParts = fullname.split(' ');
        $('input[name="field1"]').val(nameParts[0] || '');
        $('input[name="field2"]').val(nameParts.slice(1).join(' ') || '');
    });
    
    const initialTotal = subtotal + shippingFee;
    $('#final-total').text(initialTotal.toLocaleString('vi-VN') + 'đ');
});
</script>
@endsection