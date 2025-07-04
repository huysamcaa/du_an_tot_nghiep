@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Danh sách mã giảm giá</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Mã giảm giá</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
<div class="container py-4">
    <h2 class="mb-4">Danh sách mã giảm giá</h2>

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
                                    {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : number_format($coupon->discount_value) . ' VND' }}
                                </span>
                            </p>

                            <ul class="list-unstyled small mb-3">
                                @if($coupon->start_date)
                                    <li>Bắt đầu: {{ $coupon->start_date->format('d/m/Y') }}</li>
                                @endif
                                @if($coupon->end_date)
                                    <li>Kết thúc: {{ $coupon->end_date->format('d/m/Y') }}</li>
                                @endif
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
@endsection
