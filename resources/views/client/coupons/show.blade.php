@extends('client.layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Chi Tiết Mã Giảm Giá</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title text-primary">{{ $coupon->title }}</h4>

            <div class="mb-3">
                <span class="badge bg-success fs-5">{{ $coupon->code }}</span>
            </div>

            <p><strong>Mô tả:</strong> {{ $coupon->description ?? 'Không có mô tả' }}</p>
            <p><strong>Giá trị:</strong>
                {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . ' VND' }}
            </p>

            @if($coupon->start_date)
                <p><strong>Bắt đầu:</strong> {{ $coupon->start_date->format('d/m/Y') }}</p>
            @endif
            @if($coupon->end_date)
                <p><strong>Kết thúc:</strong> {{ $coupon->end_date->format('d/m/Y') }}</p>
            @endif

            @if($coupon->restriction)
                <hr>
                <h5>Điều Kiện Áp Dụng</h5>
                <p>Đơn tối thiểu: {{ number_format($coupon->restriction->min_order_value) }} VND</p>
                <p>Giảm tối đa: {{ number_format($coupon->restriction->max_discount_value) }} VND</p>
            @endif


            <a href="{{ route('client.coupons.active') }}" class="btn btn-outline-info btn-sm mt-3 ms-2">Quay lại</a>
        </div>
    </div>
</div>
@endsection
