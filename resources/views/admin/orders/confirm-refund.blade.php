@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Xác nhận hoàn tiền đơn hàng #{{ $order->code }}</h1>
                <a href="{{ route('admin.orders.cancelled', $order->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>
@if($order->check_refund_cancel == 1)
<div class="alert alert-success alert-dismissible fade show">
    <h4><i class="fas fa-check-circle"></i> ĐÃ HOÀN TIỀN THÀNH CÔNG</h4>
    <hr>
    
    @if($order->img_send_refund_money)
    <div class="mb-3">
        <h5><i class="fas fa-images"></i> Ảnh minh chứng:</h5>
        <div class="row">
            @foreach(json_decode($order->img_send_refund_money) as $image)
            <div class="col-md-3 mb-3">
                <img src="{{ asset('storage/'.$image) }}" class="img-thumbnail">
                <small class="d-block text-center mt-1">{{ basename($image) }}</small>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <p><strong><i class="fas fa-calendar-alt"></i> Thời gian hoàn tiền:</strong> 
               {{ $order->updated_at->format('H:i d/m/Y') }}</p>
        </div>
        <div class="col-md-6">
            <p><strong><i class="fas fa-user-shield"></i> Người xác nhận:</strong> 
               {{ $order->updater->name ?? 'Hệ thống' }}</p>
        </div>
    </div>
</div>
@endif
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Khách hàng</label>
                                <p class="font-weight-bold">{{ $order->user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Tổng tiền</label>
                                <p class="font-weight-bold text-danger">{{ number_format($order->total_amount) }}đ</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Lý do hủy</label>
                                <p class="font-weight-bold">{{ $order->currentStatus->cancel_reason }}</p>
                            </div>
                        </div>
                    </div>

                    @if($order->currentStatus->bank_name)
                    <hr>
                    <div class="mt-4">
                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-university mr-2"></i>Thông tin hoàn tiền
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Ngân hàng</label>
                                    <p class="font-weight-bold">{{ $order->currentStatus->bank_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Số tài khoản</label>
                                    <p class="font-weight-bold">{{ $order->currentStatus->account_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Tên tài khoản</label>
                                    <p class="font-weight-bold">{{ $order->currentStatus->account_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Số điện thoại</label>
                                    <p class="font-weight-bold">{{ $order->currentStatus->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success">
                    <h6 class="m-0 font-weight-bold text-white">Xác nhận hoàn tiền</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.confirm-refund', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="evidence_images" class="font-weight-bold">Ảnh minh chứng chuyển tiền <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="evidence_images" name="evidence_images[]" multiple required>
                                <label class="custom-file-label" for="evidence_images">Chọn file ảnh...</label>
                            </div>
                            <small class="form-text text-muted">Định dạng: JPEG, PNG, JPG, GIF (tối đa 2MB/ảnh)</small>
                        </div>
                        @if(session('images'))
                        <div class="alert alert-success mt-3">
                            <h5><i class="fas fa-check-circle"></i> Ảnh đã tải lên thành công:</h5>
                            <div class="row mt-2">
                                @foreach(session('images') as $image)
                                <div class="col-md-3 mb-3">
                                    <img src="{{ asset('storage/'.$image) }}" class="img-thumbnail">
                                    <small class="d-block text-center mt-1">{{ basename($image) }}</small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- <div class="form-group">
                            <label for="notes" class="font-weight-bold">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Nhập ghi chú nếu cần..."></textarea>
                        </div> --}}

                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                            <i class="fas fa-check-circle mr-2"></i>XÁC NHẬN ĐÃ HOÀN TIỀN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Hiển thị tên file khi chọn
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var files = e.target.files;
        var label = document.querySelector('.custom-file-label');
        
        if (files.length > 1) {
            label.textContent = files.length + ' files selected';
        } else if (files.length === 1) {
            label.textContent = files[0].name;
        }
    });
</script>
@endsection