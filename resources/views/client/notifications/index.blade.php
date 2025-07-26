@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2 class="display-4 ">Thông Báo</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}" class="text-decoration-none text-dark">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span class="text-muted">Thông báo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container mt-5">
        <h1 class="text-center mb-4 ">Thông báo của bạn</h1>

        @if ($notifications->isEmpty())
            <div class="alert alert-info">Bạn chưa nhận được thông báo nào.</div>
        @else
            <div class="row">
                @foreach ($notifications as $notification)
                    <div class="col-md-4">
                        <div class="card mb-3 {{ $notification->read == 0 ? 'border-primary' : 'border-secondary' }}">
                            <div class="card-body">
                                <h5 class="card-title text-truncate">{{ $notification->message }}</h5>
                                <p class="card-text"><small class="text-muted">Được gửi lúc: {{ $notification->created_at->format('d/m/Y H:i') }}</small></p>

                                @if ($notification->read == 0)
                                    <!-- Nút "Đánh dấu là đã đọc" -->
                                    <a href="{{ route('client.notifications.markAsRead', $notification->id) }}" class="btn btn-warning btn-sm mt-2">Đánh dấu là đã đọc</a>
                                @else
                                    <!-- Thông báo đã đọc -->
                                    <span class="btn btn-success btn-sm mt-2">Thông báo đã đọc</span>
                                @endif


                                    <a href="{{ route('client.notifications.show', $notification->id) }}" class="btn btn-info btn-sm mt-2">Xem chi tiết thông báo</a>
                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
