@extends('client.layouts.app')

@section('content')
    <div class="checkoutPage">

        <!-- Banner -->
        <section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Mã Giảm Giá Đã Nhận</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Mã giảm
                                    giá</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

     
        <div class="container py-5">
            <h4 class="text-center mb-4 text-dark">Danh sách mã giảm giá đã nhận</h4>

            @auth
                <div class="text-center mb-4">
                    <a href="{{ route('client.coupons.index') }}" class="btn btn-outline-dark">
                        <i class="fas me-1"></i>Quay lại
                    </a>
                </div>
            @endauth

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning text-center">{{ session('warning') }}</div>
            @endif

            @if ($coupons->isEmpty())
                <div class="alert alert-info text-center">Hiện không có mã giảm giá nào đang hoạt động.</div>
            @else
                <div class="row g-4">
                    @foreach ($coupons as $coupon)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border border-info rounded-3 shadow-sm">
                                <div class="card-body d-flex flex-column p-4">


                                    <div class="text-center mb-3">
                                        <h5 class="text-uppercase fw-bold text-info">{{ $coupon->code }}</h5>
                                        <p class="text-muted small mb-2">{{ $coupon->title }}</p>
                                        <span class="badge bg-info text-white fs-6 px-3 py-2">
                                            {{ $coupon->discount_type === 'percent'
                                                ? rtrim(rtrim(number_format($coupon->discount_value, 2, '.', ''), '0'), '.') . '%'
                                                : number_format($coupon->discount_value, 0, ',', '.') . ' VNĐ' }}
                                        </span>

                                    </div>


                                    <div class="mb-2 small text-muted">
                                        <strong>Nhóm áp dụng:</strong>
                                        {{ $coupon->user_group ? ucfirst($coupon->user_group) : 'Tất cả người dùng' }}
                                    </div>


                                    <ul class="list-unstyled small text-muted mb-2">
                                        @if ($coupon->start_date)
                                            <li><i class="far fa-calendar me-1"></i> Bắt đầu:
                                                {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</li>
                                        @endif
                                        @if ($coupon->end_date)
                                            <li><i class="far fa-clock me-1"></i> Kết thúc:
                                                {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</li>
                                        @endif
                                    </ul>


                                    @if ($coupon->restriction)
                                        <div class="small text-muted mb-3">
                                            <div>💰 Tối thiểu:
                                                {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} VNĐ
                                            </div>
                                            <div>🔻 Tối đa:
                                                {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }}
                                                VNĐ</div>
                                        </div>
                                    @endif


                                    <div class="mt-auto">
                                        <a href="{{ route('client.coupons.show', $coupon->id) }}"
                                            class="btn btn-primary btn-sm w-100 mb-2">
                                            Xem chi tiết
                                        </a>

                                        @auth
                                            @php
                                                $claimed = auth()->user()->coupons->contains($coupon->id);
                                            @endphp

                                            @if (!$claimed)
                                                <form action="{{ route('client.coupons.claim', $coupon->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm w-100 text-white">
                                                        Nhận mã
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">
                                                Đăng nhập để nhận mã
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
    </div>
@endsection
