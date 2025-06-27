@extends('admin.layouts.app')

@section('content')
<h1>Danh sách đơn hàng COD</h1>

<a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Về trang quản trị</a>

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
                            <li class="active">Danh sách đơn hàng COD</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.orders.index') }}" class="mb-3">
    <div class="input-group" style="max-width:400px;">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm mã đơn, tên khách hàng..." value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
    </div>
</form>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Ngày đặt</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{ $order->code }}</td>
            <td>{{ $order->fullname }}</td>
            <td>{{ $order->created_at }}</td>
            <td>
                @if($order->is_paid)
                    <span class="badge bg-success">Đã thanh toán</span>
                @else
                    <span class="badge bg-warning">Chưa thanh toán</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">Xem</a>
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Xóa đơn hàng?')" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Không có đơn hàng nào.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $orders->links() }}
</div>

@endsection