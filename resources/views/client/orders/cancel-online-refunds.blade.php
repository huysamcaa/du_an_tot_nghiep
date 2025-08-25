@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Đơn hàng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết hoàn tiền</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-4 text-center">Chi tiết hoàn tiền đơn hàng #{{ $order->code }}</h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3 border-bottom pb-2">Thông tin chi tiết</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-3">
                                <li class="mb-2"><strong>Mã đơn hàng:</strong> <span class="text-primary">{{ $order->code }}</span></li>
                                <li class="mb-2"><strong>Tên khách hàng:</strong> {{ $order->user->name ?? 'N/A' }}</li>
                                <li class="mb-2"><strong>Ngày hoàn:</strong> {{ $order->updated_at ? $order->updated_at->format('H:i d/m/Y') : 'N/A' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-3">
                                <li class="mb-2"><strong>Số tiền hoàn:</strong> <span class="text-danger fw-bold fs-5">{{ number_format($order->total_amount ?? 0) }} VNĐ</span></li>
                                <li class="mb-2"><strong>Trạng thái:</strong> <span class="badge bg-success">Đã hoàn tiền</span></li>
                                <li class="mb-2"><strong>Lý do hủy:</strong> {{ $order->currentStatus->cancel_reason ?? 'N/A' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->check_refund_cancel == 1)
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">ĐÃ HOÀN TIỀN THÀNH CÔNG</h5>
                            <p class="text-muted mb-0">Chúng tôi đã xử lý yêu cầu hoàn tiền của bạn.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><i class="fas fa-calendar-alt me-2 text-muted"></i> <strong>Thời gian:</strong> {{ $order->updated_at ? $order->updated_at->format('H:i d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><i class="fas fa-user-shield me-2 text-muted"></i> <strong>Người xác nhận:</strong> {{ $order->updater->name ?? 'Hệ thống' }}</p>
                        </div>
                    </div>
                    
                    @if($order->img_send_refund_money)
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3">Ảnh minh chứng <i class="fas fa-images ms-1"></i></h6>
                    <div class="row g-3">
                        @foreach(json_decode($order->img_send_refund_money) as $image)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <a href="{{ asset('storage/'.$image) }}" data-lightbox="refund-images" class="d-block text-center">
                                <img src="{{ asset('storage/'.$image) }}" class="img-fluid rounded shadow-sm border" alt="Ảnh minh chứng hoàn tiền">
                                <small class="d-block text-muted mt-1 text-truncate">{{ basename($image) }}</small>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="text-end">
                <a href="{{ url()->previous() }}" class="btn btn-primary rounded-5 px-4">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection