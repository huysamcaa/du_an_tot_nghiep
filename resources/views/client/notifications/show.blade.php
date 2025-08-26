@extends('client.layouts.app')
@section('title','Chi tiết thông báo')
@section('content')
<style>
    .pageBannerSection { background:#ECF5F4; padding: 10px 0; }
    .pageBannerContent h2 { font-size: 38px; color:#52586D; font-family: 'Jost', sans-serif; margin-bottom: 10px; }
    .pageBannerPath { font-size: 14px; }
    .badge-read {
        background-color: #22c55e; color:#fff; border-radius:999px; padding:6px 12px;
        font-weight:600; display:inline-flex; align-items:center; gap:6px; font-size:13px;
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
    .card-rounded { border-radius: 16px; }
    .custom-btn {
        background-color:#94B7B9; color:#fff; border:none; border-radius:20px;
        padding:8px 16px; display:inline-flex; align-items:center; gap:6px;
        font-weight:600; transition:.2s; white-space:nowrap; min-width:120px; height:40px;
    }
    .custom-btn:hover { background-color:#7fa1a3; }
</style>

<section class="pageBannerSection">
    <div class="container">
        <div class="row"><div class="col-lg-12">
            <div class="pageBannerContent text-center">
                <h2>Chi tiết thông báo</h2>
                <div class="pageBannerPath">
                    <a href="{{ route('client.home') }}">Trang chủ</a>
                    &nbsp;&nbsp;>&nbsp;&nbsp;
                    <a href="{{ route('client.notifications.index') }}">Thông báo</a>
                    &nbsp;&nbsp;>&nbsp;&nbsp;
                    <span>Chi tiết</span>
                </div>
            </div>
        </div></div>
    </div>
</section>

<div class="container py-5">
    <div class="card shadow-sm card-rounded">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-bell text-warning"></i>
                    <h4 class="mb-0">Thông báo</h4>
                </div>

                @if ($notification->read == 1)
                    <span class="badge-read">
                        <i class="fas fa-check-circle"></i> Đã đọc
                    </span>
                @endif
            </div>

            <div class="text-muted small mb-3">
                <i class="far fa-clock me-1"></i>
                {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
            </div>

            <hr>

            <div class="fs-6">
                {{ $notification->message }}
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('client.notifications.index') }}" class="custom-btn">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>

@endsection
