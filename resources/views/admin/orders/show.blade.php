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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Khách hàng:</strong> {{ $order->fullname }}
                            </div>
                            <div class="mb-2">
                                <strong>SĐT:</strong> {{ $order->phone_number }}
                            </div>
                            <div>
                                <strong>Email:</strong> {{ $order->email ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mb-2">
                                <strong>Địa chỉ:</strong> {{ $order->address }}
                            </div>
                            <div>
                                <strong>Ghi chú:</strong> {{ $order->notes ?? 'Không có' }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
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
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <strong>Trạng thái thanh toán:</strong>
                                <span class="badge bg-{{ $order->is_paid ? 'success' : 'warning' }}">
                                    {{ $order->is_paid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <strong>Trạng thái đơn hàng:</strong>
                                <span class="badge bg-{{ getStatusColor($order->currentStatus?->orderStatus?->slug ?? 'pending') }}">
                                    {{ $order->currentStatus?->orderStatus?->name ?? 'Chờ xử lý' }}
                                </span>
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
                                @foreach ($order->items as $item)
                                @php
                                    $product = \App\Models\Admin\Product::find($item->product_id);
                                @endphp
                                <tr>
                                    <td>
                                        @if ($product && $product->thumbnail)
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <div class="small text-muted">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                        @if ($product)
                                            <div class="small text-muted">Thương hiệu: {{ $product->brand->name ?? 'N/A' }}</div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->variant->price, 0, ',', '.') }} đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-bold">
                                        {{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }} đ
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Tạm tính:</th>
                                    <th>{{ number_format($order->total_amount - 30000, 0, ',', '.') }} đ</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Phí vận chuyển:</th>
                                    <th>30.000 đ</th>
                                </tr>
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
                                        {{ $status->name }}
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
                    <h5 class="mb-0">Lịch sử trạng thái</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach (\App\Models\Admin\OrderOrderStatus::where('order_id', $order->id)->orderBy('created_at', 'desc')->get() as $history)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">{{ $history->orderStatus->name ?? '—' }}</span>
                                <span class="text-muted small">{{ $history->created_at->format('d/m/Y H:i') }}</span>
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
@endphp