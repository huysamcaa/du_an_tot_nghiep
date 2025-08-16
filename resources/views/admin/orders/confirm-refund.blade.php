@extends('admin.layouts.app')

@section('title', 'Xác nhận hoàn tiền đơn hàng #'.$order->code)

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Xác nhận hoàn tiền</h4>
            <h6>Đơn hàng #{{ $order->code }}</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.orders.cancelled') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
        </div>
    </div>

    @if($order->check_refund_cancel == 1)
    <div class="alert alert-success alert-dismissible fade show">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div>
                <h5 class="mb-1">ĐÃ HOÀN TIỀN THÀNH CÔNG</h5>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <p class="mb-1"><i class="fas fa-calendar-alt me-2"></i> <strong>Thời gian:</strong> 
                           {{ $order->updated_at ? $order->updated_at->format('H:i d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><i class="fas fa-user-shield me-2"></i> <strong>Người xác nhận:</strong> 
                           {{ $order->updater->name ?? 'Hệ thống' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($order->img_send_refund_money)
        <hr>
        <div class="mt-3">
            <h6 class="fw-bold"><i class="fas fa-images me-2"></i>Ảnh minh chứng:</h6>
            <div class="row g-3 mt-2">
                @foreach(json_decode($order->img_send_refund_money) as $image)
                <div class="col-md-3 col-6">
                    <div class="border rounded p-2">
                        <img src="{{ asset('storage/'.$image) }}" class="img-fluid rounded">
                        <small class="d-block text-center text-muted mt-1">{{ basename($image) }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Khách hàng</label>
                                <p class="fw-bold">{{ $order->user->name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Tổng tiền</label>
                                <p class="fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Lý do hủy</label>
                                <p class="fw-bold">{{ $order->currentStatus->cancel_reason ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Ngày hủy</label>
                                <p class="fw-bold">{{ $order->currentStatus->created_at ? $order->currentStatus->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($order->currentStatus && $order->currentStatus->bank_name)
                    <hr>
                    <div class="mt-4">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-university me-2"></i>Thông tin hoàn tiền
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Ngân hàng</label>
                                    <p class="fw-bold">{{ $order->currentStatus->bank_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Số tài khoản</label>
                                    <p class="fw-bold">{{ $order->currentStatus->account_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Tên tài khoản</label>
                                    <p class="fw-bold">{{ $order->currentStatus->account_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Số điện thoại</label>
                                    <p class="fw-bold">{{ $order->currentStatus->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Xác nhận hoàn tiền</h5>
                </div>
                <div class="card-body">
                    @if($order->check_refund_cancel == 0)
                    <form action="{{ route('admin.orders.confirm-refund', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="evidence_images" class="form-label fw-bold">Ảnh minh chứng chuyển tiền <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="evidence_images" name="evidence_images[]" multiple required>
                            <div class="form-text">Định dạng: JPEG, PNG, JPG, GIF (tối đa 2MB/ảnh)</div>
                        </div>
                        
                        @if(session('images'))
                        <div class="alert alert-success mt-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle me-2"></i>
                                <h6 class="mb-0 fw-bold">Ảnh đã tải lên thành công:</h6>
                            </div>
                            <div class="row g-2 mt-2">
                                @foreach(session('images') as $image)
                                <div class="col-6 col-md-4">
                                    <div class="border rounded p-1">
                                        <img src="{{ asset('storage/'.$image) }}" class="img-fluid rounded">
                                        <small class="d-block text-center text-muted">{{ basename($image) }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="fas fa-check-circle me-2"></i>XÁC NHẬN ĐÃ HOÀN TIỀN
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Đơn hàng đã được hoàn tiền</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert-success {
        border-left: 4px solid #28a745;
    }
    .alert-info {
        border-left: 4px solid #17a2b8;
    }
    .img-fluid {
        max-height: 100px;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script>
    // Hiển thị tên file khi chọn
    document.getElementById('evidence_images')?.addEventListener('change', function(e) {
        var files = e.target.files;
        var label = this.nextElementSibling;
        
        if (files.length > 1) {
            label.textContent = files.length + ' files selected';
        } else if (files.length === 1) {
            label.textContent = files[0].name;
        }
    });
</script>
@endpush