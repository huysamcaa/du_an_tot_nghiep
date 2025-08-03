@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Kết quả thanh toán</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkoutResult">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="resultCard">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                        <div class="orderInfo">
                            <p><strong>Mã đơn hàng:</strong> {{ $order->code }}</p>
                            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}₫</p>
                            <p><strong>Phương thức:</strong> 
                                @if($order->payment_id == 3) MoMo
                                @elseif($order->payment_id == 4) VNPay
                                @else Thanh toán khi nhận hàng
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('client.orders.show', $order->code) }}" class="ulinaBTN">
                            <span>Xem chi tiết đơn hàng</span>
                        </a>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                        <a href="{{ route('cart.index') }}" class="ulinaBTN">
                            <span>Quay lại giỏ hàng</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection