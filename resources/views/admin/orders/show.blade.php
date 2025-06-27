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
    <strong>Trạng thái:</strong>
    {!! $order->is_paid ? '<span class="badge bg-success">Đã thanh toán</span>' : '<span class="badge bg-warning">Chưa thanh toán</span>' !!}
    <br>
    <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" style="display:inline;">
        @csrf
        @if(!$order->is_paid)
            <button type="submit" class="btn btn-success btn-sm mt-2">Xác nhận đã thanh toán COD</button>
        @endif
    </form>
</div>

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
@endsection
       
    