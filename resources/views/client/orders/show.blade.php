@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->code)

@section('content')
<!-- Banner Section -->
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Lịch Sử Mua Hàng</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Lịch Sử Mua Hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Detail Content -->
<div class="container my-5">
    <div class="row g-4">
        <!-- Customer Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user me-2 text-primary"></i>Thông tin người đặt
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->user)
                    <div class="d-flex align-items-center mb-3">
                        @if($order->user->avatar)
                            <img src="{{ asset('storage/' . $order->user->avatar) }}" 
                                 class="rounded-circle me-3" 
                                 width="60" 
                                 height="60" 
                                 alt="Avatar">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user text-muted fs-4"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $order->user->fullname ?? $order->user->name }}</h6>
                            <small class="text-muted">Thành viên từ: {{ $order->user->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endif
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Tài khoản:</span>
                            <span>{{ $order->user->email ?? 'Khách vãng lai' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Điện thoại:</span>
                            <span>{{ $order->user->phone_number ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Giới tính:</span>
                            <span>
                                @if($order->user && $order->user->gender)
                                    @if($order->user->gender === 'male')
                                        Nam
                                    @elseif($order->user->gender === 'female')
                                        Nữ
                                    @else
                                        Khác
                                    @endif
                                @else
                                    Không xác định
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Ngày sinh:</span>
                            <span>{{ $order->user->birthday ? \Carbon\Carbon::parse($order->user->birthday)->format('d/m/Y') : 'Không có' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Điểm tích lũy:</span>
                            <span>{{ $order->user->loyalty_points ?? 0 }} điểm</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Receiver Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-truck me-2 text-primary"></i>Thông tin người nhận
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Mã đơn hàng:</span>
                            <span class="fw-bold">#{{ $order->code }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Người nhận:</span>
                            <span>{{ $order->fullname }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Điện thoại:</span>
                            <span>{{ $order->phone_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Email:</span>
                            <span>{{ $order->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Địa chỉ:</span>
                            <span class="text-end">{{ $order->address }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Ngày đặt:</span>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-0 px-0 py-2">
                            <span class="fw-medium">Trạng thái:</span>
                            <span>
                                @if($order->is_paid)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-clock me-1"></i>Chưa thanh toán
                                    </span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item border-0 px-0 pt-2 pb-0">
                            <div class="d-flex">
                                <span class="fw-medium me-2">Ghi chú:</span>
                                <span>{{ $order->note ?? 'Không có ghi chú' }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-12 mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shopping-basket me-2 text-primary"></i>Chi tiết đơn hàng
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3" style="width: 60px; height: 60px; overflow: hidden; border-radius: 8px;">
                                                @if($item->product && $item->product->thumbnail)
                                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                                        alt="{{ $item->name }}" 
                                                        class="img-fluid h-100 w-100 object-fit-cover">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 w-100">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $item->name }}</h6>
                                                <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                   <td class="text-end">
                                        @if($item->variant)
                                            {{ number_format($item->variant->price) }}đ
                                        @else
                                            {{ number_format($item->product->price) }}đ
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 fw-bold">{{ number_format($item->price * $item->quantity) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4">Tổng tiền hàng</td>
                                    <td class="text-end fw-bold pe-4">{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; })) }}đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4">Phí vận chuyển</td>
                                    <td class="text-end fw-bold pe-4">30.000đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4">Tổng thanh toán</td>
                                    <td class="text-end fw-bold text-danger pe-4">{{ number_format($order->total_amount) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('client.orders.purchase.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <a href="{{ route('client.home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
  
    .pageBannerContent h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
        background-color: #fff;
    }
    .list-group-item {
        padding: 12px 0;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        background-color: #f8f9fa !important;
    }
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
        font-size: 0.75rem;
    }
    .bg-opacity-10 {
        background-color: rgba(var(--bs-success-rgb), 0.1);
    }
</style>
@endsection