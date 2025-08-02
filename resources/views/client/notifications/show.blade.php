@extends('client.layouts.app')

@section('content')
 <div class="checkoutPage">

        <section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Chi tiết thông báo</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Chi tiết thông báo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<div class="container mt-5 mb-5">
    <h4 class="text-center mb-4 text-primary">📩 Chi tiết thông báo</h4>

    @php
        $typeMap = [
            1 => 'Mã giảm giá',
            2 => 'Đơn hàng',
            3 => 'Thông báo hệ thống',
        ];
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white fw-semibold">
            {{ $notification->message }}
        </div>

        <div class="card-body">
            <p class="mb-3">
                <span class="badge bg-light text-dark border border-secondary">
                    🔔 {{ $typeMap[$notification->type] ?? 'Không xác định' }}
                </span>
            </p>

            @if ($notification->coupon_id)
                <p>
                    <strong>Mã giảm giá:</strong>
                    <a href="{{ route('client.coupons.show', $notification->coupon_id) }}" class="btn btn-sm btn-outline-success ms-2">
                        Xem chi tiết
                    </a>
                </p>
            @elseif ($notification->order_id)
                <p>
                    <strong>Đơn hàng:</strong>
                    <a href="{{ route('client.orders.show', $notification->order_id) }}" class="btn btn-sm btn-outline-secondary ms-2">
                        Xem chi tiết
                    </a>
                </p>
            @endif

            <hr>

            <p class="text-muted mb-0">
                🕒 Gửi lúc: {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
            </p>

            <div class="mt-4">
                <a href="{{ route('client.notifications.index') }}" class="btn btn-outline-dark">
                    Quay lại danh sách thông báo
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
