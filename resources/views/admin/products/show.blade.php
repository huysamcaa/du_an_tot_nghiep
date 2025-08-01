@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-box-seam me-2"></i>Chi tiết sản phẩm
        </h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-6">
            <!-- Basic Information Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr>
                                    <th width="30%" class="text-muted">ID</th>
                                    <td>
                                        <span class="badge bg-light text-dark">#{{ $product->id }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tên sản phẩm</th>
                                    <td class="fw-semibold">{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nhà sản xuất</th>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ optional($product->brand)->name ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Số lượng tổng</th>
                                    <td>
                                        <span class="fw-semibold {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Giá gốc</th>
                                    <td class="fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Mô tả ngắn</th>
                                    <td class="fst-italic text-muted">{{ $product->short_description }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Mô tả chi tiết</th>
                                    <td class="text-muted">{!! nl2br(e($product->description)) !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Images Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-images me-2"></i>Hình ảnh sản phẩm
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="d-flex align-items-center text-muted">
                            <i class="bi bi-card-image me-2"></i>Ảnh đại diện
                        </h5>
                        @if($product->thumbnail)
                            <div class="border rounded p-2 d-inline-block">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 200px; width: auto;">
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle me-1"></i>Không có ảnh đại diện
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <h5 class="d-flex align-items-center text-muted">
                            <i class="bi bi-collection me-2"></i>Ảnh chi tiết
                        </h5>
                        @if($product->galleries && $product->galleries->count())
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->galleries as $gallery)
                                    <div class="border rounded p-1">
                                        <img src="{{ asset('storage/' . $gallery->image) }}" 
                                             style="width: 100px; height: 100px; object-fit: cover;" 
                                             class="img-thumbnail">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-1"></i>Không có ảnh chi tiết
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
            <!-- Order Statistics Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-graph-up me-2"></i>Thống kê đơn hàng
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light-info shadow-none">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="bi bi-cart3 fs-3"></i>
                                    </div>
                                    <h6 class="text-muted">Tổng đơn hàng</h6>
                                    <h3 class="mb-0 fw-bold">{{ $totalOrders }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light-success shadow-none">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="bi bi-check-circle fs-3"></i>
                                    </div>
                                    <h6 class="text-muted">Đã bán</h6>
                                    <h3 class="mb-0 fw-bold">{{ $totalSold }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light-warning shadow-none">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2">
                                        <i class="bi bi-currency-dollar fs-3"></i>
                                    </div>
                                    <h6 class="text-muted">Doanh thu</h6>
                                    <h3 class="mb-0 fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }} đ</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light-primary shadow-none">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="bi bi-box-seam fs-3"></i>
                                    </div>
                                    <h6 class="text-muted">Tồn kho</h6>
                                    <h3 class="mb-0 fw-bold">{{ $product->stock }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="d-flex align-items-center text-muted mb-3">
                        <i class="bi bi-bar-chart me-2"></i>Chi tiết theo trạng thái
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    
                                    <th class="text-end">Số đơn</th>
                                    <th class="text-end">Số lượng</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
    @forelse($orderStats as $stat)
    <tr>
        
        <td class="text-end">{{ $stat['order_count'] ?? 0 }}</td>
        <td class="text-end">{{ $stat['total_quantity'] ?? 0 }}</td>
        <td class="text-end fw-semibold">{{ number_format($stat['total_revenue'] ?? 0, 0, ',', '.') }} đ</td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-center py-3 text-muted">
            <i class="bi bi-info-circle me-1"></i>Không có dữ liệu thống kê
        </td>
    </tr>
    @endforelse
</tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-info text-white py-3">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-clock-history me-2"></i>Đơn hàng gần đây
                    </h4>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th class="text-end">SL</th>
                                        <th class="text-end">Thành tiền</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $orderItem)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $orderItem->order_id) }}" class="text-primary">
                                                #{{ $orderItem->order->code ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 100px;">
                                                {{ $orderItem->order->customer->name ?? 'Khách vãng lai' }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $orderItem->quantity }}</td>
                                        <td class="text-end fw-semibold">{{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }} đ</td>
                                        <td>
                                            @switch($orderItem->order->status)
                                                @case(1)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-hourglass me-1"></i>Chờ xử lý
                                                    </span>
                                                    @break
                                                @case(2)
                                                    <span class="badge bg-info text-dark">
                                                        <i class="bi bi-gear me-1"></i>Đang xử lý
                                                    </span>
                                                    @break
                                                @case(3)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Hoàn thành
                                                    </span>
                                                    @break
                                                @case(4)
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Đã hủy
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $orderItem->order->status }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-1"></i>Chưa có đơn hàng nào cho sản phẩm này
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Variants Card -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 d-flex align-items-center">
                <i class="bi bi-boxes me-2"></i>Danh sách biến thể
            </h4>
        </div>
        <div class="card-body">
            @if($product->variants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="100">Ảnh</th>
                                <th>SKU</th>
                                <th class="text-end">Giá</th>
                                <th class="text-end">Số lượng</th>
                                <th>Thuộc tính</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $variant)
                            <tr>
                                <td>
                                    @if($variant->thumbnail)
                                        <img src="{{ asset('storage/' . $variant->thumbnail) }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $variant->sku ?? '-' }}</span>
                                </td>
                                <td class="text-end fw-semibold text-danger">
                                    {{ number_format($variant->price, 0, ',', '.') }} đ
                                </td>
                                <td class="text-end">
                                    <span class="{{ $variant->stock > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $variant->stock }}
                                    </span>
                                </td>
                                <td>
                                    @foreach($variant->attributeValues as $value)
                                        <span class="badge bg-primary me-1 mb-1">
                                            {{ $value->attribute->name }}: {{ $value->value }}
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-1"></i>Sản phẩm này không có biến thể
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-header {
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }
    .table th {
        font-weight: 500;
    }
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .bg-light-primary {
        background-color: rgba(13,110,253,.1);
    }
    .bg-light-success {
        background-color: rgba(25,135,84,.1);
    }
    .bg-light-info {
        background-color: rgba(13,202,240,.1);
    }
    .bg-light-warning {
        background-color: rgba(255,193,7,.1);
    }
</style>
@endsection