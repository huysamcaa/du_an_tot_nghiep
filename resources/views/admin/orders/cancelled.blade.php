@extends('admin.layouts.app')

@section('title', 'Danh Sách Đơn Hàng Đã Hủy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh Sách Đơn Hàng Đã Hủy</h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.orders.cancelled') }}" method="GET">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách Hàng</th>
                                <th>Tổng Tiền</th>
                                <th>PT Thanh Toán</th>
                                <th>Lý Do Hủy</th>
                                <th>Ngày Hủy</th>
                                <th>Hoàn Tiền</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->code }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ number_format($order->total_amount) }}đ</td>
                                <td>
                                    @if($order->payment_id == 2)
                                         COD
                                    @elseif($order->payment_id == 3)
                                        Ví Momo
                                    @elseif($order->payment_id == 4)
                                        Ví VNPay
                                    @else
                                        Không Xác Định
                                    @endif
                                </td>
                                <td>{{ $order->currentStatus->cancel_reason }}</td>
                                <td>{{ $order->currentStatus->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($order->payment_id == 3 && $order->payment_id == 4)
                                        @if($order->check_refund_cancel == 1)
                                            <span class="badge bg-success">Đã hoàn tiền</span>
                                        @else
                                            <span class="badge bg-warning">Chờ hoàn tiền</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Không áp dụng</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if($order->payment_id == 3 || $order->payment_id == 4)
                                        <a href="{{ route('admin.orders.confirm-refund', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có đơn hàng nào đã hủy</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $orders->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection