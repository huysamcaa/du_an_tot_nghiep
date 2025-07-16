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
    <h2 class="mb-4">Danh sách mã giảm giá đang hoạt động</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    @if($coupons->isEmpty())
        <div class="alert alert-info">Hiện tại chưa có mã giảm giá nào đang hoạt động.</div>
    @else
        <div class="row">
            @foreach($coupons as $coupon)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">
                                <strong>{{ $coupon->code }}</strong>
                            </h5>

                            <p class="text-muted">{{ $coupon->title }}</p>

                            <p>
                                <span class="badge bg-success">
                                    {{ $coupon->discount_type === 'percent'
                                        ? $coupon->discount_value . '%'
                                        : number_format($coupon->discount_value) . ' VND' }}
                                </span>
                            </p>

                            @if($coupon->user_group)
                                <p><strong>Nhóm áp dụng:</strong> {{ ucfirst($coupon->user_group) }}</p>
                            @endif

                            <ul class="list-unstyled small mb-3">
                                @if($coupon->start_date)
                                    <li>Bắt đầu: {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</li>
                                @endif
                                @if($coupon->end_date)
                                    <li>Kết thúc: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</li>
                                @endif
                            </ul>

                            @if($coupon->restriction)
                                <small class="text-muted">Đơn tối thiểu: {{ number_format($coupon->restriction->min_order_value) }} VND</small><br>
                                <small class="text-muted">Giảm tối đa: {{ number_format($coupon->restriction->max_discount_value) }} VND</small>
                            @endif

                            <a href="{{ route('client.coupons.show', $coupon->id) }}" class="btn btn-outline-primary mt-3">
                                Xem chi tiết
                            </a>

                            @php
                                $claimed = auth()->user()->coupons->contains($coupon->id);
                            @endphp

                            @if(!$claimed)
                                <form action="{{ route('client.coupons.claim', $coupon->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100">Nhận mã</button>
                                </form>
                            @else
                                <span class="badge bg-secondary mt-2 w-100 text-center">Đã nhận</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection

