@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Chi tiết danh mục</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>ID:</strong> {{ $category->id }}
                </div>
                <div class="col-md-6">
                    <strong>Tên danh mục:</strong> {{ $category->name }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Slug:</strong> {{ $category->slug }}
                </div>
                <div class="col-md-6">
                    <strong>Danh mục cha:</strong> {{ $category->parent ? $category->parent->name : 'Không có' }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Icon:</strong> {!! $category->icon !!}
                </div>
                <div class="col-md-6">
                    <strong>Thứ tự:</strong> {{ $category->ordinal }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Trạng thái:</strong>
                    @if($category->is_active)
                        <span class="badge badge-success">Hiển thị</span>
                    @else
                        <span class="badge badge-secondary">Ẩn</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <strong>Ngày tạo:</strong> {{ $category->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Ngày cập nhật:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Sản phẩm thuộc danh mục này</h5>
    </div>
    <div class="card-body">
        @if($products && $products->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Thương hiệu</th>
                            <th>Giá gốc</th>
                            <th>Giá sale</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                
                                <td>
                                        @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60">
                                        @else
                                        <span>Không có ảnh</span>
                                        @endif
                                    </td>
                
                                <td>
                                    {{ $product->name }}
                                    <div class="small text-muted">
                                        @if($product->is_featured)
                                            <span class="badge badge-primary mr-1">Nổi bật</span>
                                        @endif
                                        @if($product->is_trending)
                                            <span class="badge badge-warning mr-1">Xu hướng</span>
                                        @endif
                                        @if($product->is_sale)
                                            <span class="badge badge-danger">Đang sale</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $brandName = '';
                                        switch($product->brand_id) {
                                            case 1: $brandName = 'Nike'; break;
                                            case 2: $brandName = 'Adidas'; break;
                                            // Thêm các case khác tương ứng
                                            default: $brandName = 'Khác';
                                        }
                                    @endphp
                                    {{ $brandName }}
                                </td>
                                <td class="text-right">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td class="text-right">
                                    @if($product->sale_price)
                                        {{ number_format($product->sale_price, 0, ',', '.') }} đ
                                        @if($product->sale_price_start_at && $product->sale_price_end_at)
                                            <div class="small text-muted">
                                                {{ date('d/m/Y', strtotime($product->sale_price_start_at)) }} - 
                                                {{ date('d/m/Y', strtotime($product->sale_price_end_at)) }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $product->stock }}</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-success">Hiển thị</span>
                                    @else
                                        <span class="badge badge-secondary">Ẩn</span>
                                    @endif
                                    @if($product->deleted_at)
                                        <span class="badge badge-danger">Đã xóa</span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y H:i', strtotime($product->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Không có sản phẩm nào trong danh mục này.</p>
        @endif
    </div>
</div>

    <div class="mt-4">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>
@endsection