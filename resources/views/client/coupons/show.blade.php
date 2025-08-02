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
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;
                            <a href="{{ route('client.coupons.index') }}">Mã giảm giá</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;
                            <span>Chi tiết</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="container py-5">
        <div class="card border border-info shadow-sm rounded-3">
            <div class="card-body px-4 py-4">


                <h4 class="text-info fw-bold mb-3">
                    <i class="fas fa-ticket-alt me-2"></i>{{ $coupon->title }}
                </h4>

                <div class="mb-3">
                    <span class="badge bg-info text-white fs-5 px-3 py-2">{{ $coupon->code }}</span>
                </div>


                <p><strong>Mô tả:</strong> {{ $coupon->description ?? 'Không có mô tả' }}</p>


                <p>
                    <strong>Giá trị ưu đãi:</strong>
                    {{ $coupon->discount_type === 'percent'
                        ? $coupon->discount_value . '%'
                        : number_format($coupon->discount_value, 0, ',', '.') . ' VND' }}
                </p>


                @if($coupon->user_group)
                    <p><strong>Nhóm áp dụng:</strong> {{ ucfirst($coupon->user_group) }}</p>
                @endif
<div class="row mb-3">
    @if($coupon->start_date)
        <div class="col-12 mb-2">
            <p>
                <i class="far fa-calendar-alt me-1 text-muted"></i>
                <strong>Bắt đầu:</strong> {{ $coupon->start_date->format('d/m/Y H:i') }}
            </p>
        </div>
    @endif

    @if($coupon->end_date)
        <div class="col-12">
            <p>
                <i class="far fa-clock me-1 text-muted"></i>
                <strong>Kết thúc:</strong> {{ $coupon->end_date->format('d/m/Y H:i') }}
            </p>
        </div>
    @endif
</div>



                @if($coupon->usage_limit)
                    <p><strong>Số lần sử dụng tối đa:</strong> {{ $coupon->usage_limit }}</p>
                @endif


                @if($coupon->restriction)
                    <hr>
                    <h5 class="text-dark">📌 Điều Kiện Áp Dụng</h5>
                    <ul class="list-unstyled ms-2">
                        <li><i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Đơn tối thiểu:</strong> {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} VND
                        </li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Giảm tối đa:</strong> {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }} VND
                        </li>
                    </ul>
                @endif


                <div class="mt-4">
                    <a href="{{ route('client.coupons.index') }}" class="btn btn-outline-info">
                        ← Quay lại danh sách mã
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
