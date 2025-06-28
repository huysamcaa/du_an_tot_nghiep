{{-- filepath: resources/views/client/orders/show.blade.php --}}
@extends('client.layouts.app')

@section('content')
<!-- Banner -->
<section class="pageBannerSection" style="background: #f1f8f8; padding: 60px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2 class="mb-2" style="font-size:48px; font-weight:600; color:#3a4d5c;">Chi Tiết Đơn Hàng</h2>
                    <div class="pageBannerPath" style="font-size:18px;">
                        <a href="{{ route('client.home') }}" style="color:#3a4d5c;">Trang chủ</a>
                        &nbsp;&nbsp;&gt;&nbsp;&nbsp;
                        <span style="color:#3a4d5c;">Chi Tiết Đơn Hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nội dung chi tiết đơn hàng -->
<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <h5 class="mb-3" style="color:#3a4d5c;">Thông tin khách hàng</h5>
                    <ul class="list-unstyled mb-0" style="font-size:16px;">
                        <li><strong>Khách hàng:</strong> {{ $order->fullname }}</li>
                        <li><strong>Địa chỉ:</strong> {{ $order->address }}</li>
                        <li><strong>Số điện thoại:</strong> {{ $order->phone_number }}</li>
                        <li><strong>Email:</strong> {{ $order->email }}</li>
                        <li><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
                        <li>
                            <strong>Trạng thái:</strong>
                            @if($order->is_paid)
                                <span class="badge" style="background:#3ecf8e; color:#fff;">Đã thanh toán</span>
                            @else
                                <span class="badge" style="background:#ffe066; color:#3a4d5c;">Chưa thanh toán</span>
                            @endif
                        </li>
                        <li><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <h5 class="mb-3" style="color:#3a4d5c;">Danh sách sản phẩm</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" style="border-radius: 12px; overflow: hidden;">
                            <thead style="background: #f1f8f8;">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->price) }}đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price * $item->quantity) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Tổng tiền hàng</th>
                                    <td class="fw-bold" style="color:#e74c3c;">{{ number_format($order->total_amount) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <a href="{{ route('client.home') }}" class="btn mt-4" style="background:#3a4d5c; color:#fff; border-radius:8px;">
                        <i class="fa fa-home me-1"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection