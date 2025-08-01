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
                                <input type="text" name="field1" placeholder="Họ *"  required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field2" placeholder="Tên *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="field4" placeholder="Địa chỉ email *" value="{{ auth()->user()->email ?? '' }}"  required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field5" placeholder="Số điện thoại *" value="{{ auth()->user()->phone_number ?? '' }}"  required>
                            </div>
                            <div class="col-lg-12">
                                <select name="field6" style="display: none;">
                                    <option value="">Chọn quốc gia</option>
                                    <option value="VN" selected>Việt Nam</option>
                                    {{-- ...other countries... --}}
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <select name="field7" class="form-control" required>
                                    <option value="">-- Chọn địa chỉ --</option>
                                    @foreach($addresses as $address)
                                        <option value="{{ $address->address }}" 
                                            {{ old('field7', $defaultAddress->address ?? '') == $address->address ? 'selected' : '' }}>
                                            {{ $address->address }} 
                                            @if($address->fullname) ({{ $address->fullname }}) @endif
                                            @if($address->id_default) [Mặc định] @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-lg-12">
                                <input type="text" name="field7" placeholder="Địa chỉ *">
                            {{-- <div class="col-lg-12">
                                <input type="text" name="field8" placeholder="Thành phố *">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field9" placeholder="Quận/Huyện *">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field10" placeholder="Mã bưu điện *">
                            </div> --}}
                            {{-- <div class="col-lg-12">
                                
                                <div class="checkoutPassword">
                                    <input type="password" name="field12" placeholder="Mật khẩu *">
                                </div>
                            </div> --}}
                            <div class="col-lg-12">
                                <div class="shippingAddress"></div>
                            </div>
                            <div class="col-lg-12">
                                <textarea name="field14" placeholder="Ghi chú đơn hàng"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    {{-- <div class="shippingCoupons">
                        <h3>Mã giảm giá</h3>
                        <div class="couponFormWrap clearfix">
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Nhập mã giảm giá">
                            <button type="submit" class="ulinaBTN" name="apply_coupon" value="Apply Code"><span>Áp dụng</span></button>
                        </div>
                    </div> --}}
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
                                            <ins>{{ number_format($total) }}đ</ins>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr class="shippingRow">
                                    <th>Phí vận chuyển</th>
                                    <td>
                                        <div class="pi01Price">
                                            <ins>30,000đ</ins>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th>Tổng thanh toán</th>
                                    <td>
                                        <div class="pi01Price">
                                            <ins>{{ number_format($total + 30000)}}đ</ins>
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
@endsection