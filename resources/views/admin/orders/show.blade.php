@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết đơn hàng</h4>
            <h6>Thông tin chi tiết đơn hàng #{{ $order->code }}</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Thông tin đơn hàng --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="info-title"><i class="fas fa-user me-2"></i>Thông tin người đặt</h6>
                                <div class="info-content">
                                    <div class="mb-2">
                                        <strong>Họ tên:</strong> {{ $order->fullname }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>SĐT:</strong> {{ $order->phone_number }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Email:</strong> {{ $order->email ?? 'N/A' }}
                                    </div>
                                    <div>
                                        <strong>Địa chỉ:</strong> {{ $order->address }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="info-title"><i class="fas fa-truck me-2"></i>Thông tin người nhận</h6>
                                <div class="info-content">
                                    
                                    <div class="mb-2">
                                        <strong>Họ tên:</strong> {{ $order->fullname }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>SĐT:</strong> {{ $order->phone_number }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Email:</strong> {{ $order->email ?? 'N/A' }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Địa chỉ:</strong> {{ $order->address }}
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="info-title"><i class="fas fa-info-circle me-2"></i>Thông tin bổ sung</h6>
                                <div class="info-content">
                                    <div class="mb-2">
                                        <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <h6 class="info-title"><i class="fas fa-credit-card me-2"></i>Thanh toán & Vận chuyển</h6>
                                <div class="info-content">
                                    <div class="mb-2">
                                        <strong>Phương thức thanh toán:</strong>
                                        <span class="badge bg-info">
                                            @switch($order->payment_id)
                                                @case(2) COD @break
                                                @case(3) MOMO @break
                                                @case(4) VNPAY @break
                                                @default Chưa xác định
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Trạng thái thanh toán:</strong>
                                        <span class="badge bg-{{ $order->is_paid ? 'success' : 'warning' }}">
                                            {{ $order->is_paid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>Phí vận chuyển:</strong> 30.000 đ
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Danh sách sản phẩm --}}
            <div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Danh sách sản phẩm</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="80">Hình ảnh</th>
                        <th>Sản phẩm</th>
                        <th width="120">Giá</th>
                        <th width="100">SL</th>
                        <th width="150">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                        $shippingFee = $order->shipping_fee ?? 30000;
                    @endphp
                    
                    @foreach ($order->items as $item)
                    @php
                        $product = \App\Models\Admin\Product::find($item->product_id);
                        $itemPrice = $item->variant->price ?? $item->price ?? 0;
                        $itemTotal = $itemPrice * $item->quantity;
                        $subtotal += $itemTotal;
                    @endphp
                    <tr>
                        <td>
                            
                                <img src="{{ asset('storage/' . $item->variant->thumbnail) }}" width="60" class="img-thumbnail">
                            
                        </td>
                        <td>
                            <div class="fw-bold">{{ $item->name }}</div>
                           
                            {{-- Hiển thị thông tin biến thể nếu có --}}
                            @isset($item->attributes_variant)
    @foreach ($item->attributes_variant as $key => $variant)
        <span>{{ $variant['attribute_name'] }}: {{ $variant['value'] }}</span> |
    @endforeach
@endisset


                            @if ($product)
                                <div class="small text-muted">Thương hiệu: {{ $product->brand->name ?? 'N/A' }}</div>
                            @endif
                        </td>
                        <td>{{ number_format($itemPrice, 0, ',', '.') }} đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="fw-bold">
                            {{ number_format($itemTotal, 0, ',', '.') }} đ
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end">Tạm tính:</th>
                        <th>{{ number_format($subtotal, 0, ',', '.') }} đ</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Phí vận chuyển:</th>
                        <th>30.000 đ</th>
                    </tr>
                    @if($order->coupon_code)
                    <tr>
                        <th colspan="4" class="text-end">Mã giảm giá ({{ $order->coupon_code }}):</th>
                        <th>- {{ number_format($order->coupon_discount_value, 0, ',', '.') }} đ</th>
                    </tr>
                    @endif
                    <tr>
                        <th colspan="4" class="text-end">Tổng cộng:</th>
                        <th>{{ number_format($order->total_amount, 0, ',', '.') }} đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>  
        </div>

        <div class="col-lg-4">
            {{-- Cập nhật trạng thái --}}
            @php
                $currentStatusId = $order->currentStatus?->orderStatus?->id ?? null;
                $finalStatusIds = [6, 7, 8]; // Assuming these are final status IDs
                $isFinal = in_array($currentStatusId, $finalStatusIds);
            @endphp
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Cập nhật trạng thái</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Trạng thái hiện tại</label>
                            <div class="alert alert-{{ getStatusColor($order->currentStatus?->orderStatus?->slug ?? 'pending', true) }} mb-3">
                                <i class="fas {{ getStatusIcon($order->currentStatus?->orderStatus?->slug ?? 'pending') }} me-2"></i>
                                {{ $order->currentStatus?->orderStatus?->name ?? 'Chờ xử lý' }}
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="order_status_id" class="form-label">Cập nhật trạng thái mới</label>
                            <select name="order_status_id" id="order_status_id" class="form-select" required {{ $isFinal ? 'disabled' : '' }}>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ $currentStatusId == $status->id ? 'selected' : '' }}
                                        @if (
                                            !in_array($status->id, [$nextStatusId, 6, 7]) ||
                                            ($status->id == 7 && $currentStatusId != 5) ||
                                            ($status->id == 6 && $currentStatusId != 1)) disabled @endif>
                                        <i class="fas {{ getStatusIcon($status->slug) }} me-2"></i> {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100" {{ $isFinal ? 'disabled' : '' }}>
                            <i class="fas fa-save me-2"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>

            {{-- Lịch sử trạng thái --}}
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử trạng thái</h5>
                </div>
                <div class="card-body p-0">
                    <div class="order-timeline">
                        @php
                            $histories = \App\Models\Admin\OrderOrderStatus::where('order_id', $order->id)
                                ->orderBy('created_at', 'asc')
                                ->get();
                        @endphp
                        
                        @foreach ($histories as $index => $history)
                        <div class="timeline-item {{ $index === count($histories) - 1 ? 'current' : '' }}">
                            <div class="timeline-icon bg-{{ getStatusColor($history->orderStatus->slug ?? 'pending') }}">
                                <i class="fas {{ getStatusIcon($history->orderStatus->slug ?? 'pending') }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $history->orderStatus->name ?? '—' }}</h6>
                                <p class="text-muted small mb-0">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $history->created_at->format('H:i d/m/Y') }}
                                </p>
                                @if($index === count($histories) - 1)
                                    <span class="badge bg-success mt-1">Trạng thái hiện tại</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.85em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .table th {
        white-space: nowrap;
    }
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        max-width: 100%;
        height: auto;
    }
    
    /* Info Section Styles */
    .info-section {
        margin-bottom: 1.5rem;
    }
    .info-title {
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .info-content {
        padding-left: 0.5rem;
    }
    
    /* Timeline Styles */
    .order-timeline {
        padding: 15px;
    }
    .timeline-item {
        display: flex;
        position: relative;
        padding: 10px 0;
    }
    .timeline-item:not(:last-child):after {
        content: '';
        position: absolute;
        left: 21px;
        top: 45px;
        bottom: -10px;
        width: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    .timeline-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        z-index: 2;
        flex-shrink: 0;
    }
    .timeline-icon i {
        color: white;
        font-size: 18px;
    }
    .timeline-content {
        flex-grow: 1;
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 6px;
        border-left: 3px solid #6c757d;
    }
    .timeline-item.current .timeline-content {
        border-left-color: #28a745;
        background: #f0fff4;
    }
    .timeline-item.current .timeline-icon {
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);
    }
</style>
@endpush

@php
    function getStatusColor($statusSlug, $isAlert = false) {
        $colors = [
            'pending' => $isAlert ? 'warning' : 'warning',
            'processing' => $isAlert ? 'info' : 'primary',
            'shipped' => $isAlert ? 'info' : 'info',
            'delivered' => $isAlert ? 'success' : 'success',
            'cancelled' => $isAlert ? 'danger' : 'danger',
            'returned' => $isAlert ? 'secondary' : 'secondary'
        ];
        
        return $colors[$statusSlug] ?? ($isAlert ? 'light' : 'secondary');
    }
    
    function getStatusIcon($statusSlug) {
        $icons = [
            'pending' => 'fa-hourglass-half',
            'processing' => 'fa-cogs',
            'shipped' => 'fa-shipping-fast',
            'delivered' => 'fa-check-circle',
            'cancelled' => 'fa-times-circle',
            'returned' => 'fa-undo'
        ];
        
        return $icons[$statusSlug] ?? 'fa-circle';
    }
@endphp