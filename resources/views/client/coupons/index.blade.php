@extends('client.layouts.app')
@section('title','Khuyến mãi')
@section('content')

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
        @if (Auth::check())
            <div class="text-center mb-4">
                <a href="{{ route('client.coupons.received') }}" class="ulinaBTN">
                    <span class="px-3"><i class="fas fa-gift me-1"></i> Xem các mã đã nhận</span>
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
            @foreach ($coupons as $coupon)
                @php
                    $code = $coupon->code;
                    $title = $coupon->title;
                    $discountType = $coupon->discount_type;
                    $discountValue = $coupon->discount_value;
                    $minOrderValue = $coupon->restriction?->min_order_value;
                    $maxDiscountValue = $coupon->restriction?->max_discount_value;
                @endphp

                {{-- <div class="col-md-6 col-lg-4 mb-4">
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
                </div> --}}
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="coupon-card">
                        <div class="coupon-left">
                            <h5 class="discount">
                                {{ $discountType === 'percent'
                                    ? 'Giảm ' . rtrim(rtrim(number_format($discountValue, 2, '.', ''), '0'), '.') . '%'
                                    : 'Giảm ' . number_format($discountValue, 0, ',', '.') . ' VNĐ' }}
                            </h5>
                            <p class="condition">
                                Đơn tối thiểu: {{ number_format($minOrderValue, 0, ',', '.') }} VNĐ
                            </p>
                            <p class="expiry">
                            @if ($coupon->end_date)
                                    HSD: {{ $coupon->end_date?->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}
                            @endif
                            </p>
                        </div>
                        <div class="coupon-right">
                            @auth
                                <form action="{{ route('client.coupons.claim', $coupon->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-save">Lưu</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn-save">Lưu</a>
                            @endauth
                                <a href="{{ route('client.coupons.show', $coupon->id) }}">
                                    <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                                </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        </div>

@endsection
