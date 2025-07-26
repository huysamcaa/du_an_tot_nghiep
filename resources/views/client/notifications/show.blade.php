@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2 class="display-4 ">Chi Tiết Thông Báo</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}" class="text-decoration-none text-dark">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span class="text-muted">Chi tiết Thông báo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container mt-5">
        <!-- Tiêu đề trang -->
        <h1 class="text-center mb-4 ">Chi tiết thông báo</h1>

        <!-- Card thông báo -->
        <div class="card shadow-lg rounded-lg border-0 mb-4">
            <div class="card-body">
                <!-- Tiêu đề thông báo -->
                <h5 class="card-title text-center mb-3 text-dark font-weight-bold">{{ $notification->message }}</h5>

                <!-- Loại thông báo -->
                <p class="card-text mb-3">
                    <strong>Loại thông báo:</strong>
                    <span class="badge {{ $notification->type == 1 ? 'bg-warning' : 'bg-secondary' }} text-white">
                        {{ $notification->type == 1 ? 'Mã giảm giá mới' : 'Khác' }}
                    </span>
                </p>

                <!-- Ngày giờ gửi -->
                <p class="card-text text-muted">
                    <small>Được gửi lúc: {{ $notification->created_at->format('d/m/Y H:i') }}</small>
                </p>

                <!-- Nút đánh dấu đã đọc -->
                @if ($notification->read == 0)
                    <a href="{{ route('client.notifications.markAsRead', $notification->id) }}" class="btn btn-success btn-lg mt-3">Đánh dấu là đã đọc</a>
                @else
                    <span class=" btn btn-success ">Thông báo đã đọc</span>
                @endif
                <a href="{{ route('client.coupons.index') }}" class="btn btn-info" >Xem mã giảm giá</a>
            </div>
        </div>

        <!-- Nút quay lại danh sách thông báo -->
        <div class="text-center mt-4">
            <a href="{{ route('client.notifications.index') }}" class="btn btn-outline-info btn-lg">Quay lại</a>
        </div>
    </div>
</div>
@endsection
