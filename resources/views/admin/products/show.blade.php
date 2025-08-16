@extends('admin.layouts.app')

@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết sản phẩm</h4>
            <h6>Thông tin chi tiết và thống kê về sản phẩm</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                <i class="fa fa-edit me-1"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{-- Cột trái: Thông tin chính và Hình ảnh --}}
                <div class="col-lg-8">
                    <ul class="nav nav-tabs nav-tabs-solid mb-4" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab" aria-controls="basic-info" aria-selected="true">
                                <i class="fa fa-info-circle me-1"></i> Thông tin cơ bản
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#product-variants" type="button" role="tab" aria-controls="product-variants" aria-selected="false">
                                <i class="fa fa-sitemap me-1"></i> Biến thể
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="productTabContent">
                        {{-- Tab Thông tin cơ bản --}}
                        <div class="tab-pane fade show active" id="basic-info" role="tabpanel" aria-labelledby="basic-tab">
                            <div class="card p-4">
                                <div class="row g-4">
                                    {{-- Khối ảnh --}}
                                    <div class="col-lg-6 col-md-12">
                                        <div class="image-gallery-container">
                                            {{-- Ảnh chính lớn --}}
                                            <div class="main-image mb-3">
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Ảnh đại diện" class="img-fluid rounded shadow-sm">
                                            </div>
                                            {{-- Danh sách ảnh phụ --}}
                                            <div class="thumbnail-images d-flex flex-wrap gap-2">
                                                @if($product->galleries->isNotEmpty())
                                                @foreach($product->galleries as $gallery)
                                                <div class="thumbnail-item" style="width: 80px; height: 80px;">
                                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="Ảnh chi tiết" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;">
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Khối thông tin chi tiết --}}
                                    <div class="col-lg-6 col-md-12">
                                        <div class="product-details-content">
                                            <h3 class="fw-bold text-primary">{{ $product->name }}</h3>
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th class="text-muted">Mã sản phẩm</th>
                                                            <td><span class="badge bg-light text-dark">#{{ $product->id }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-muted">Nhà sản xuất</th>
                                                            <td><span class="badge bg-info text-dark">{{ optional($product->brand)->name ?? 'N/A' }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-muted">Danh mục</th>
                                                            <td><span class="badge bg-secondary">{{ optional($product->category)->name ?? 'N/A' }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-muted">Giá gốc</th>
                                                            <td class="fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-muted">Tổng tồn kho</th>
                                                            <td><span class="fw-semibold {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">{{ $product->stock }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-muted">Trạng thái</th>
                                                            <td>
                                                                @if ($product->is_active)
                                                                    <span class="badge bg-success">Hiển thị</span>
                                                                @else
                                                                    <span class="badge bg-danger">Ẩn</span>
                                                                @endif
                                                                @if ($product->is_sale)
                                                                    <span class="badge bg-warning text-dark">Đang sale</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div>
                                    <h6 class="text-muted mb-2">Mô tả ngắn</h6>
                                    <p class="fst-italic text-muted">{{ $product->short_description }}</p>
                                </div>
                                <div class="mt-4">
                                    <h6 class="text-muted mb-2">Mô tả chi tiết</h6>
                                    <div class="description-content">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab Biến thể --}}
                        <div class="tab-pane fade" id="product-variants" role="tabpanel" aria-labelledby="variants-tab">
                            <div class="card p-4">
                                <h5 class="card-title">Danh sách biến thể</h5>
                                @if($product->variants->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Ảnh</th>
                                                <th>SKU</th>
                                                <th>Tên biến thể</th>
                                                <th class="text-end">Giá</th>
                                                <th class="text-end">Số lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variants as $variant)
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="product-image-small rounded overflow-hidden shadow-sm" style="width: 60px; height: 60px;">
                                                        @if($variant->thumbnail)
                                                            <img src="{{ asset('storage/' . $variant->thumbnail) }}" alt="Ảnh biến thể" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-light text-muted">
                                                                <i class="fa fa-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="align-middle"><span class="badge bg-light text-dark">{{ $variant->sku ?? '-' }}</span></td>
                                                <td class="align-middle">
                                                    @foreach($variant->attributeValues as $value)
                                                        <span class="badge bg-primary me-1">{{ $value->attribute->name }}: {{ $value->value }}</span>
                                                    @endforeach
                                                </td>
                                                <td class="text-end align-middle fw-semibold text-danger">{{ number_format($variant->price,0,',','.') }} đ</td>
                                                <td class="text-end align-middle"><span class="fw-semibold {{ $variant->stock > 0 ? 'text-success' : 'text-danger' }}">{{ $variant->stock }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="alert alert-info mb-0 text-center">Sản phẩm này không có biến thể</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cột phải: Thống kê & Đơn hàng --}}
                <div class="col-lg-4">
                    <div class="card mb-4 shadow-sm sticky-top" style="top: 100px;">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fa fa-chart-line me-2"></i>Thống kê & Đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            {{-- Thống kê tổng quan --}}
                            <div class="row g-3 text-center mb-4">
                                @foreach([
                                    ['icon'=>'shopping-cart','label'=>'Tổng đơn hàng','value'=>$totalOrders,'color'=>'primary'],
                                    ['icon'=>'check-circle','label'=>'Đã bán','value'=>$totalSold,'color'=>'success'],
                                    ['icon'=>'dollar-sign','label'=>'Doanh thu','value'=>number_format($totalRevenue,0,',','.') . ' đ','color'=>'warning'],
                                    ['icon'=>'cubes','label'=>'Tồn kho','value'=>$product->stock,'color'=>'primary'],
                                ] as $stat)
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <i class="fa fa-{{ $stat['icon'] }} fs-4 text-{{ $stat['color'] }} mb-2"></i>
                                        <div class="text-muted">{{ $stat['label'] }}</div>
                                        <div class="fw-bold fs-5">{{ $stat['value'] }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <hr class="my-4">
                            {{-- Đơn hàng gần đây --}}
                            <h6 class="text-muted mb-2"><i class="fa fa-history me-2"></i>Đơn hàng gần đây</h6>
                            @if($recentOrders->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Khách hàng</th>
                                            <th class="text-end">SL</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $orderItem)
                                        <tr>
                                            <td><a href="{{ route('admin.orders.show', $orderItem->order_id) }}" class="text-primary">#{{ $orderItem->order->code ?? 'N/A' }}</a></td>
                                            <td><span class="d-inline-block text-truncate" style="max-width: 100px;">{{ $orderItem->order->customer->name ?? 'Khách vãng lai' }}</span></td>
                                            <td class="text-end">{{ $orderItem->quantity }}</td>
                                            <td>
                                                
                                                {{-- @switch($orderItem->order->status)
                                                    @case(1) <span class="badge bg-warning text-dark">Chờ xử lý</span> @break
                                                    @case(2) <span class="badge bg-info text-dark">Đang xử lý</span> @break
                                                    @case(3) <span class="badge bg-success">Hoàn thành</span> @break
                                                    @case(4) <span class="badge bg-danger">Đã hủy</span> @break
                                                    @default <span class="badge bg-secondary">{{ $orderItem->order->status }}</span>
                                                @endswitch --}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="alert alert-info mb-0 text-center">Chưa có đơn hàng nào cho sản phẩm này</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection