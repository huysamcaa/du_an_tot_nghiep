@extends('admin.layouts.app')

@section('content')
<h1>Danh sách đơn hàng COD</h1><br>

<a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Về trang quản trị</a>
<br>

<table  id="bootstrap-data-table" class="table table-striped table-bordered">
    
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-3">
    <div class="input-group" style="max-width:1500px;">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm mã đơn, tên khách hàng..." value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
    </div>
</form>
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
                <span class="badge bg-info">
                    {{ $order->currentStatus?->orderStatus?->name ?? 'Chưa Thanh Toán' }}
                </span>
                <br>
                {{-- <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @if(!$order->is_paid)
                        <button type="submit" class="btn btn-success btn-sm mt-2">Xác nhận đã thanh toán COD</button>
                    @endif
                </form> --}}
            </td>
            <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">Xem</a>
                
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