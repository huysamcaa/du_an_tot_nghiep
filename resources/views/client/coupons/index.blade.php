@extends('client.layouts.app')

@section('content')
<style>
    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {
        
        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    .checkoutPage {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
</style>


    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Khuyến Mãi</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Khuyến
                                mãi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
        <div class="container py-5">
            <h4 class="mb-4 text-center text-dark">🎁 Danh sách mã giảm giá đang hoạt động</h4>

    </section>

        @if (Auth::check())
            <div class="text-center mb-4">
                <a href="{{ route('client.coupons.received') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-gift me-1"></i> Xem các mã đã nhận
                </a>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if ($coupons->isEmpty())
            <div class="alert alert-info text-center">Hiện không có mã giảm giá nào đang hoạt động.</div>
        @else
            <div class="row">
    <div class="row">
    @foreach ($coupons as $coupon)
        @php
    $code = $coupon->code;
    $title = $coupon->title;
    $discountType = $coupon->discount_type;
    $discountValue = $coupon->discount_value;
    $minOrderValue = $coupon->restriction?->min_order_value;
    $maxDiscountValue = $coupon->restriction?->max_discount_value;
@endphp

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border border-info shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">

                    <div class="mb-3 text-center">
                        <h5 class="card-title text-uppercase text-info fw-bold mb-1">{{ $code }}</h5>
                        <p class="text-muted small mb-2">{{ $title }}</p>
                        <span class="badge bg-info text-white fs-6 px-3 py-2">
                            {{ $discountType === 'percent'
                                ? rtrim(rtrim(number_format($discountValue, 2, '.', ''), '0'), '.') . '%'
                                : number_format($discountValue, 0, ',', '.') . ' VNĐ' }}
                        </span>
                    </div>

                    <p class="small mb-1">
                        👥 <strong>Nhóm áp dụng:</strong>
                        {{ $coupon->user_group ? ucfirst($coupon->user_group) : 'Tất cả người dùng' }}
                    </p>

                    <ul class="list-unstyled small text-muted mb-2">
                        @if ($coupon->start_date)
                            <li>⏱️ Bắt đầu:
                                {{ $coupon->start_date->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                            </li>
                        @endif
                        @if ($coupon->end_date)
                            <li>⏰ Kết thúc:
                                {{ $coupon->end_date->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                            </li>
                        @endif
                    </ul>

                    @if ($minOrderValue || $maxDiscountValue)
                        <div class="small text-muted mb-2">
                            <div>💰 Đơn tối thiểu: {{ number_format($minOrderValue, 0, ',', '.') }} VNĐ</div>
                            @if (!is_null($maxDiscountValue))
                                <div>🧾 Giảm tối đa: {{ number_format($maxDiscountValue, 0, ',', '.') }} VNĐ</div>
                            @endif
                            <div class="text-muted fst-italic small mt-1">
                                * Điều kiện này sẽ được <strong>lưu cố định</strong> khi bạn nhận mã
                            </div>
                        </div>
                    @endif

                    <div class="mt-auto">
                        <a href="{{ route('client.coupons.show', $coupon->id) }}"
                           class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                        </a>

                        @auth
    <form action="{{ route('client.coupons.claim', $coupon->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success btn-sm w-100">
            <i class="fas fa-plus-circle me-1"></i> Nhận mã
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">
        <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập để nhận mã
    </a>
@endauth

                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>


        @endif
    </div>

@endsection
