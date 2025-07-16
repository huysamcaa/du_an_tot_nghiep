@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Chi Tiết Mã Giảm Giá</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Mã giảm giá</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title text-primary">{{ $coupon->title }}</h4>

            <div class="mb-3">
                <span class="badge bg-success fs-5">{{ $coupon->code }}</span>
            </div>

            <p><strong>Mô tả:</strong> {{ $coupon->description ?? 'Không có mô tả' }}</p>

            <p><strong>Giá trị:</strong> {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . ' VND' }}</p>

            @if($coupon->user_group)
                <p><strong>Nhóm áp dụng:</strong> {{ ucfirst($coupon->user_group) }}</p>
            @endif

            @if($coupon->start_date)
                <p><strong>Bắt đầu:</strong> {{ $coupon->start_date->format('d/m/Y H:i') }}</p>
            @endif

            @if($coupon->end_date)
                <p><strong>Kết thúc:</strong> {{ $coupon->end_date->format('d/m/Y H:i') }}</p>
            @endif

            @if($coupon->usage_limit)
                <p><strong>Số lần sử dụng tối đa:</strong> {{ $coupon->usage_limit }}</p>
            @endif

            @if($coupon->restriction)
                <hr>
                <h5>Điều Kiện Áp Dụng</h5>
                <p><strong>Đơn tối thiểu:</strong> {{ number_format($coupon->restriction->min_order_value) }} VND</p>
                <p><strong>Giảm tối đa:</strong> {{ number_format($coupon->restriction->max_discount_value) }} VND</p>
            @endif

            <a href="{{ route('client.coupons.active') }}" class="btn btn-outline-info btn-sm mt-3">Quay lại</a>
        </div>
    </div>
</div>
</div>
@endsection
