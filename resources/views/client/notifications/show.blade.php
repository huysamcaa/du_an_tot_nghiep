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
.pageBannerSection {
    padding: 20px 0; 
    min-height: 10px; 
}

.pageBannerSection .pageBannerContent h2 {
    font-size: 38px; 
    margin-bottom: 10px;
}
.pageBannerPath {
    font-size: 14px;
}
</style>

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

<div class="container mt-5 mb-5 notification-detail">
    <h4 class="text-center mb-4 text-primary">📩 Chi tiết thông báo</h4>

    @php
        $typeMap = [
            1 => 'Mã giảm giá',
            2 => 'Đơn hàng',
            3 => 'Thông báo hệ thống',
        ];
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light fw-semibold text-dark">
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
                    <a href="{{ route('client.coupons.show', $notification->coupon_id) }}" 
                       class="btn btn-sm btn-success ms-2">
                        Xem chi tiết
                    </a>
                </p>
            @elseif ($notification->order_id)
                <p>
                    <strong>Đơn hàng:</strong>
                    <a href="{{ route('client.orders.show', $notification->order_id) }}" 
                       class="btn btn-sm btn-secondary ms-2">
                        Xem chi tiết
                    </a>
                </p>
            @endif

            <hr>

            <p class="text-muted mb-0">
                🕒 Gửi lúc: {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
            </p>

            <div class="mt-4 text-start">
                <a href="{{ route('client.notifications.index') }}" class="btn btn-dark">
                    ⬅ Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
/* ======= Notification Detail Page ======= */

.notification-detail {
    max-width: 800px;
    margin: 0 auto;
}

.notification-detail .card {
    border-radius: 12px;
    overflow: hidden;
    background: #f9fdfc;
}

.notification-detail .card-header {
    font-size: 18px;
    padding: 15px 20px;
    background: #f1f9f9 !important;
    border-bottom: 1px solid #e0e6e3;
}

.notification-detail .badge {
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 8px;
    background: #e6f4f1;
}

.notification-detail p {
    font-size: 15px;
}

.notification-detail .btn {
    border-radius: 20px;
    padding: 6px 16px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    box-shadow: none;
}

/* Nút theo chuẩn danh sách */
.notification-detail .btn-success {
    background: #5a6268;
    border: none;
}

.notification-detail .btn-success:hover {
    background: #5a6268;
}

.notification-detail .btn-secondary {
    background: #6c757d;
    border: none;
}

.notification-detail .btn-secondary:hover {
    background: #5a6268;
}

.notification-detail .btn-dark {
    background: #6c757d;
    border: none;
}

.notification-detail .btn-dark:hover {
    background: #5a6268;
}
</style>
