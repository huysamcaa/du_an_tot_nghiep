@extends('admin.layouts.app')
@section('content')
<h1>Chi tiết đơn hàng: {{ $order->code }}</h1>

<a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mb-3">Về danh sách đơn hàng</a>

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Admin</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Trang chủ</a></li>
                            <li><a href="#">Đơn hàng</a></li>
                            <li class="active">Chi tiết đơn hàng</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <strong>Khách hàng:</strong> {{ $order->fullname }}<br>
    <strong>Địa chỉ:</strong> {{ $order->address }}<br>
    <strong>Số điện thoại:</strong> {{ $order->phone_number }}<br>
    <strong>Trạng thái thanh toán:</strong>
    {!! $order->is_paid ? '<span class="badge bg-success">Đã thanh toán</span>' : '<span class="badge bg-warning">Chưa thanh toán</span>' !!}
    <br>
    <strong>Trạng thái đơn hàng:</strong>
    <span class="badge bg-info">
        {{ $order->currentStatus?->orderStatus?->name ?? 'Chưa có trạng thái' }}
    </span>
    <br>
    <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" style="display:inline;">
        @csrf
        @if(!$order->is_paid)
            <button type="submit" class="btn btn-success btn-sm mt-2">Xác nhận đã thanh toán COD</button>
        @endif
    </form>
</div>
@php
    $currentStatusId = $order->currentStatus?->orderStatus?->id ?? null;
    $finalStatusIds = [5, 6]; // 5: Đã giao hàng, 6: Đã hủy
    $isFinal = in_array($currentStatusId, $finalStatusIds);
@endphp

<form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-3">
    @csrf
    <div class="input-group" style="max-width:300px;">
        <select name="order_status_id" class="form-control" required {{ $isFinal ? 'disabled' : '' }}>
            <option value="">-- Chọn trạng thái --</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}"
                    {{ $currentStatusId == $status->id ? 'selected' : '' }}
                    @if(in_array($status->id, $usedStatusIds)) disabled @endif
                >
                    {{ $status->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary" {{ $isFinal ? 'disabled' : '' }}>Cập nhật</button>
    </div>
    @if($errors->has('order_status_id'))
        <div class="alert alert-danger mt-2">
            {{ $errors->first('order_status_id') }}
        </div>
    @endif
</form>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ number_format($item->price) }}đ</td>
            <td>{{ $item->quantity }}</td>
        </tr>
          @endforeach
    </tbody>
</table>
<h4>Lịch sử trạng thái đơn hàng</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Trạng thái</th>
            <th>Thời gian</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\Admin\OrderOrderStatus::where('order_id', $order->id)->orderBy('created_at')->get() as $history)
        <tr>
            <td>{{ $history->orderStatus->name ?? '' }}</td>
            <td>{{ $history->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

