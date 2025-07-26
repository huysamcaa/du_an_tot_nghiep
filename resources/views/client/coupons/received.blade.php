@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Mã đã nhận</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a>
                        <a href="{{ route('client.coupons.index') }}">Mã giảm giá</a>

                            <span>Đã nhận</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <h4 class="mb-4">Danh sách mã bạn đã nhận</h4>
   <a href="{{ route('client.coupons.index') }}" class="btn btn-outline-info btn-sm mt-3">Quay lại</a><br>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if ($coupons->isEmpty())
            <div class="alert alert-info">Bạn chưa nhận mã giảm giá nào.</div>
        @else
            <div class="row">
                @foreach ($coupons as $coupon)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">
                                    <strong>{{ $coupon->code }}</strong>
                                </h5>

                                <p class="text-muted">{{ $coupon->title }}</p>

                                <p>
                                    <span class="badge bg-success px-3 py-2 fs-6">
                                        {{ $coupon->discount_type === 'percent'
                                            ? $coupon->discount_value . '%'
                                            : number_format($coupon->discount_value, 0, ',', '.') . ' VNĐ' }}
                                    </span>
                                </p>

                                @if ($coupon->user_group)
                                    <p><strong>Nhóm áp dụng:</strong> {{ ucfirst($coupon->user_group) }}</p>
                                @endif

                                @if ($coupon->start_date)
                                    <p><strong>Hiệu lực từ:</strong> {{ $coupon->start_date->format('d/m/Y') }}</p>
                                @endif

                                @if ($coupon->end_date)
                                    <p><strong>Hết hạn:</strong> {{ $coupon->end_date->format('d/m/Y') }}</p>
                                @endif

                                @if ($coupon->restriction)
                                    <small class="text-muted d-block">Đơn tối thiểu:
                                        {{ number_format($coupon->restriction->min_order_value) }} VND</small>
                                    <small class="text-muted d-block">Giảm tối đa:
                                        {{ number_format($coupon->restriction->max_discount_value) }} VND</small>
                                @endif

                                <a href="{{ route('client.coupons.show', $coupon->id) }}"
                                   class="btn btn-outline-info mt-3">
                                    Xem chi tiết
                                </a>


                                <span class="badge bg-secondary mt-2 text-center w-100">Đã nhận</span>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
