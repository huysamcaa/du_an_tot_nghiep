@extends('client.layouts.app')

@section('content')


        <section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Khuyến Mãi</h2>
                    <div class="pageBannerPath">
                        <a href="{{route('client.home')}}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Khuyến mãi</span>
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
                    @foreach ($coupons as $coupon)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border border-info shadow-sm h-100">
                                <div class="card-body d-flex flex-column justify-content-between">


                                    <div class="mb-3 text-center">
                                        <h5 class="card-title text-uppercase text-info fw-bold mb-1">
                                            {{ $coupon->code }}
                                        </h5>
                                        <p class="text-muted small mb-2">{{ $coupon->title }}</p>
                                        <span class="badge bg-info text-white fs-6 px-3 py-2">
                                            {{ $coupon->discount_type === 'percent'
                                                ? rtrim(rtrim(number_format($coupon->discount_value, 2, '.', ''), '0'), '.') . '%'
                                                : number_format($coupon->discount_value, 0, ',', '.') . ' VNĐ' }}
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


                                    @if ($coupon->restriction)
                                        <div class="small text-muted mb-2">
                                            <div>💰 Đơn tối thiểu:
                                                {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} VNĐ
                                            </div>
                                            <div>🧾 Giảm tối đa:
                                                {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }}
                                                VNĐ</div>
                                        </div>
                                    @endif

                                    <div class="mt-auto">
                                        <a href="{{ route('client.coupons.show', $coupon->id) }}"
                                            class="btn btn-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-info-circle me-1"></i> Xem chi tiết
                                        </a>

                                        @auth
                                            @php
                                                $claimed = auth()->user()->coupons->contains($coupon->id);
                                            @endphp

                                            @if (!$claimed)
                                                <form action="{{ route('client.coupons.claim', $coupon->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-plus-circle me-1"></i> Nhận mã
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-warning w-100 py-2 text-center">Đã nhận</span>
                                            @endif
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
