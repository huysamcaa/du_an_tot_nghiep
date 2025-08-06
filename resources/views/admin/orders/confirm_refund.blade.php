@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Xác nhận hoàn tiền đơn hàng #{{ $order->code }}</h2>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5>Thông tin đơn hàng</h5>
            <p>Khách hàng: {{ $order->user->name }}</p>
            <p>Tổng tiền: {{ number_format($order->total) }}đ</p>
            <p>Phương thức thanh toán: {{ $order->paymentMethod->name }}</p>
            <p>Lý do hủy: {{ $order->currentStatus->cancel_reason }}</p>
            
            @if($order->currentStatus->bank_name)
            <div class="mt-3">
                <h5>Thông tin hoàn tiền</h5>
                <p>Ngân hàng: {{ $order->currentStatus->bank_name }}</p>
                <p>Tên tài khoản: {{ $order->currentStatus->account_name }}</p>
                <p>Số tài khoản: {{ $order->currentStatus->account_number }}</p>
                <p>Số điện thoại: {{ $order->currentStatus->phone_number }}</p>
            </div>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.orders.confirm-refund', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="evidence_images">Ảnh minh chứng chuyển tiền *</label>
            <input type="file" name="evidence_images[]" id="evidence_images" class="form-control" multiple required>
            <small class="text-muted">Có thể tải lên nhiều ảnh (JPEG, PNG, JPG, GIF)</small>
        </div>

        <div class="form-group">
            <label for="notes">Ghi chú</label>
            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Xác nhận đã hoàn tiền</button>
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection