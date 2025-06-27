@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Checkout</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Home</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Checkout</span>
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
                    <div class="loginLinks">
                        <p>Already have an account? <a href="{{ route('login') }}">Click Here to Login</a></p>
                    </div>
                    <div class="checkoutForm">
                        <h3>Your Billing Address</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="field1" placeholder="First Name *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field2" placeholder="Last Name *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="field4" placeholder="Email address *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field5" placeholder="Phone *" required>
                            </div>
                            <div class="col-lg-12">
                                <select name="field6" style="display: none;">
                                    <option value="">Select a country</option>
                                    <option value="VN" selected>Vietnam</option>
                                    {{-- ...other countries... --}}
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" name="field7" placeholder="Address *" required>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" name="field8" placeholder="City/Town *">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field9" placeholder="State *">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="field10" placeholder="Zip Code *">
                            </div>
                            <div class="col-lg-12">
                                <div class="checkoutRegister">
                                    <input type="checkbox" value="1" name="field11" id="is_register">
                                    <label for="is_register">Create Account?</label>
                                </div>
                                <div class="checkoutPassword">
                                    <input type="password" name="field12" placeholder="Account Password *">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="shippingAddress"></div>
                            </div>
                            <div class="col-lg-12">
                                <textarea name="field14" placeholder="Order Note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shippingCoupons">
                        <h3>Coupon Code</h3>
                        <div class="couponFormWrap clearfix">
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Write your Coupon Code">
                            <button type="submit" class="ulinaBTN" name="apply_coupon" value="Apply Code"><span>Apply Code</span></button>
                        </div>
                    </div>
                    <div class="orderReviewWrap">
                        <h3>Your Order</h3>
                        <div class="orderReview">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);">{{ $item->product->name }}</a>
                                        </td>
                                        <td>
                                            <div class="pi01Price">
                                                <ins>{{ number_format($item->product->price * $item->quantity) }}đ</ins>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Tổng tiền hàng</th>
                                        <td>
                                            <div class="pi01Price">
                                                <ins>{{ number_format($total) }}đ</ins>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="shippingRow">
                                        <th>Tiền phí vận chuyển</th>
                                        <td>
                                            <div class="pi01Price">
                                                <ins>30,000đ</ins>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tổng tiền thanh toán</th>
                                        <td>
                                            <div class="pi01Price">
                                                <ins>{{ number_format($total + 30000)}}đ</ins>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <ul class="wc_payment_methods">
                                <li>
                                    <input type="radio" value="1" name="paymentMethod" id="paymentMethod01">
                                    <label for="paymentMethod01">Direct bank transfer</label>
                                    <div class="paymentDesc shows">
                                        Arkono ridoy venge tumi met, consectetur adipisicing elit, sed do eiusmod tempor incidid gna aliqua.
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" value="4" name="paymentMethod" id="paymentMethod04">
                                    <label for="paymentMethod04">Payment by cheque</label>
                                    <div class="paymentDesc">
                                        Arkono ridoy venge tumi met, consectetur adipisicing elit, sed do eiusmod tempor incidid gna aliqua.
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" value="2" name="paymentMethod" id="paymentMethod02" checked>
                                    <label for="paymentMethod02">Cash on delivery</label>
                                    <div class="paymentDesc">
                                        Arkono ridoy venge tumi met, consectetur adipisicing elit, sed do eiusmod tempor incidid gna aliqua.
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" value="3" name="paymentMethod" id="paymentMethod03">
                                    <label for="paymentMethod03">Paypal</label>
                                    <div class="paymentDesc">
                                        Arkono ridoy venge tumi met, consectetur adipisicing elit, sed do eiusmod tempor incidid gna aliqua.
                                    </div>
                                </li>
                            </ul>
                            <button type="submit" class="placeOrderBTN ulinaBTN"><span>Place Order</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection