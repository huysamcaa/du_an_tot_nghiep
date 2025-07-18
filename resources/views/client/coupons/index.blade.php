@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Mã Giảm Giá Của Bạn</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Mã giảm giá</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <h2 class="mb-4">Mã Giảm Giá Bạn Đã Nhận</h2>

    @if($coupons->isEmpty())
        <div class="alert alert-warning">Bạn chưa có mã giảm giá nào.</div>
    @else
        <div class="row">
            @foreach($coupons as $coupon)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <span class="badge bg-success">{{ $coupon->code }}</span>
                            </h5>

                            <p class="mb-1"><strong>{{ $coupon->title }}</strong></p>

                            <p>
                                <span class="badge bg-info text-dark">
                                    {{ $coupon->discount_type == 'percent'
                                        ? $coupon->discount_value . '%'
                                        : number_format($coupon->discount_value) . ' VND' }}
                                </span>
                            </p>

                            <ul class="list-unstyled small">
                                <li><strong>Số lượng:</strong> {{ $coupon->pivot->amount }}</li>
                                <li><strong>Ngày nhận:</strong> {{ $coupon->pivot->created_at->format('d/m/Y H:i') }}</li>
                                <li><strong>Hạn dùng:</strong>
                                    @if($coupon->start_date || $coupon->end_date)
                                        {{ $coupon->start_date ? $coupon->start_date->format('d/m/Y') : '...' }}
                                        -
                                        {{ $coupon->end_date ? $coupon->end_date->format('d/m/Y') : '...' }}
                                    @else
                                        <span class="text-muted">Không thời hạn</span>
                                    @endif
                                </li>
                            </ul>

                            <a href="{{ route('client.coupons.show', $coupon->id) }}" class="btn btn-outline-primary mt-auto">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection
