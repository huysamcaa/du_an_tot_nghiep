@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Mã giảm giá</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Mã giảm giá</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <h4 class="mb-3">Danh sách mã giảm giá đang hoạt động</h4>

        @if (Auth::check())
            <a href="{{ route('client.coupons.received') }}" class="btn btn-outline-secondary mb-3">Mã đã nhận</a>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if ($coupons->isEmpty())
            <div class="alert alert-info">Không có mã giảm giá nào đang hoạt động.</div>
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
                                            ? rtrim(rtrim(number_format($coupon->discount_value, 2, '.', ''), '0'), '.') . '%'
                                            : number_format($coupon->discount_value, 0, ',', '.') . ' VNĐ' }}
                                    </span>
                                </p>

                                {{-- Nhóm người dùng áp dụng --}}
                                <p>
                                    <strong>Nhóm áp dụng:</strong>
                                    {{ $coupon->user_group ? ucfirst($coupon->user_group) : 'Tất cả người dùng' }}
                                    <i class="fas fa-info-circle text-muted" title="Chỉ người thuộc nhóm này mới thấy và nhận được mã."></i>
                                </p>

                                <ul class="list-unstyled small mb-3">
                                    @if ($coupon->start_date)
                                        <li>Bắt đầu: {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</li>
                                    @endif
                                    @if ($coupon->end_date)
                                        <li>Kết thúc: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</li>
                                    @endif
                                </ul>

                                {{-- Điều kiện hạn chế nếu có --}}
                                @if ($coupon->restriction)
                                    <small class="text-muted d-block">Đơn tối thiểu: {{ number_format($coupon->restriction->min_order_value) }} VND</small>
                                    <small class="text-muted d-block">Giảm tối đa: {{ number_format($coupon->restriction->max_discount_value) }} VND</small>
                                @endif

                                <a href="{{ route('client.coupons.show', $coupon->id) }}" class="btn btn-outline-info mt-3">Xem chi tiết</a>

                                {{-- Nút nhận mã --}}
                                @auth
                                    @php
                                        $claimed = auth()->user()->coupons->contains($coupon->id);
                                    @endphp

                                    @if (!$claimed)
                                        <form action="{{ route('client.coupons.claim', $coupon->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100">Nhận mã</button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary mt-2 w-100 text-center">Đã nhận</span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary mt-2 w-100">Đăng nhập để nhận mã</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
