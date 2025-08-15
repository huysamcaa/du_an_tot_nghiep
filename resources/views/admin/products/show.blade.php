@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-box-seam me-2"></i>Chi tiết sản phẩm
        </h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left mr-1"></i>Quay lại
        </a>
    </div>

    <div class="row gx-4 gy-4">
        <!-- Left Column: Info & Images -->
        <div class="col-lg-6">
            <!-- Basic Info -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h5>
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

            <!-- Images -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-images me-2"></i>Hình ảnh sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="bi bi-card-image me-2"></i>Ảnh đại diện</h6>
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" class="img-fluid rounded" style="max-height:200px;">
                        @else
                            <div class="alert alert-warning mb-0">Không có ảnh đại diện</div>
                        @endif
                    </div>

                    <div>
                        <h6 class="text-muted mb-2"><i class="bi bi-collection me-2"></i>Ảnh chi tiết</h6>
                        @if($product->galleries->isNotEmpty())
                            <div class="row g-2">
                                @foreach($product->galleries as $gallery)
                                    <div class="col-4 col-md-3">
                                        <img src="{{ asset('storage/' . $gallery->image) }}" class="img-fluid rounded" style="object-fit:cover; height:100px;">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-0">Không có ảnh chi tiết</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Recent Orders -->
        <div class="col-lg-6">
            <!-- Order Statistics -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Thống kê đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        @foreach([
                            ['icon'=>'cart3','label'=>'Tổng đơn hàng','value'=>$totalOrders,'color'=>'primary'],
                            ['icon'=>'check-circle','label'=>'Đã bán','value'=>$totalSold,'color'=>'success'],
                            ['icon'=>'currency-dollar','label'=>'Doanh thu','value'=>number_format($totalRevenue,0,',','.') . ' đ','color'=>'warning'],
                            ['icon'=>'box-seam','label'=>'Tồn kho','value'=>$product->stock,'color'=>'primary'],
                        ] as $stat)
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-{{ $stat['icon'] }} fs-4 text-{{ $stat['color'] }} mb-2"></i>
                                    <div class="text-muted">{{ $stat['label'] }}</div>
                                    <div class="fw-bold fs-5">{{ $stat['value'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h6 class="text-muted mb-2"><i class="bi bi-bar-chart me-2"></i>Chi tiết theo trạng thái</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
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
                                        <td class="text-end fw-semibold">{{ number_format($stat['total_revenue'] ?? 0,0,',','.') }} đ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Không có dữ liệu thống kê</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Đơn hàng gần đây</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th class="text-end">SL</th>
                                        <th class="text-end">Thành tiền</th>
                                       
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
                                                {{ $orderItem->order->customer->name ?? 'Đỗ Quang Huy' }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $orderItem->quantity }}</td>
                                        <td class="text-end fw-semibold">{{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }} đ</td>
                                        <td>
                                            {{-- @switch($orderStats->order_order_status->order_status_id )
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
                                            @endswitch --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">Chưa có đơn hàng nào cho sản phẩm này</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Variants Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-boxes me-2"></i>Danh sách biến thể</h5>
                </div>
                <div class="card-body">
                    @if($product->variants->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
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
                                                    <img src="{{ asset('storage/' . $variant->thumbnail) }}" class="img-thumbnail" style="width:60px; height:60px; object-fit:cover;">
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td><span class="badge bg-light text-dark">{{ $variant->sku ?? '-' }}</span></td>
                                            <td class="text-end fw-semibold text-danger">{{ number_format($variant->price,0,',','.') }} đ</td>
                                            <td class="text-end"><span class="fw-semibold {{ $variant->stock > 0 ? 'text-success' : 'text-danger' }}">{{ $variant->stock }}</span></td>
                                            <td>
                                                @foreach($variant->attributeValues as $value)
                                                    <span class="badge bg-primary me-1 mb-1">{{ $value->attribute->name }}: {{ $value->value }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">Sản phẩm này không có biến thể</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
