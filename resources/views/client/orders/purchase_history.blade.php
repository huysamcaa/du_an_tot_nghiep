@extends('client.layouts.app')

@section('title', 'Lịch sử mua hàng')
<section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Lịch sử mua hàng</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Giỏ hàng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
@section('content')
<div class="container py-4">
    <h2>Lịch sử mua hàng</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $order->currentStatus?->orderStatus?->name ?? 'Chưa có trạng thái' }}
                            </span>
                
            </td>
                        <td>
                            <a href="{{ route('client.orders.show', $order->code) }}" class="btn btn-info btn-sm">Xem</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Bạn chưa có đơn hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection