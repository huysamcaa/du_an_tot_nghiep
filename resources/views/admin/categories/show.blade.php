@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Chi tiết danh mục</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>
                            <li class="active">Chi tiết</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">

        {{-- Chi tiết danh mục --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thông tin cơ bản</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>ID:</strong> {{ $category->id }}</div>
                    <div class="col-md-4"><strong>Tên:</strong> {{ $category->name }}</div>
                    <div class="col-md-4"><strong>Slug:</strong> {{ $category->slug }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Danh mục cha:</strong>
                        {{ $category->parent ? $category->parent->name : '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Icon:</strong>
                        {!! $category->icon !!}
                    </div>
                    <div class="col-md-4">
                        <strong>Thứ tự:</strong> {{ $category->ordinal }}
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-4">
                        <strong>Trạng thái:</strong>
                        @if($category->is_active)
                            <span class="badge badge-success">Hiển thị</span>
                        @else
                            <span class="badge badge-secondary">Ẩn</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Ngày tạo:</strong> {{ $category->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Ngày cập nhật:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Sản phẩm thuộc danh mục --}}
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Sản phẩm liên quan ({{ $products->count() }})</h5>
            </div>
            <div class="card-body p-0">
                @if($products->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên</th>
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
                                    <td class="p-2">
                                        @if($product->thumbnail)
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="thumb" class="img-thumbnail" style="width:60px; height:60px; object-fit:cover;">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        {{ $product->name }}
                                        <div class="small">
                                            @if($product->is_featured)
                                                <span class="badge badge-primary">Nổi bật</span>
                                            @endif
                                            @if($product->is_trending)
                                                <span class="badge badge-warning">Xu hướng</span>
                                            @endif
                                            @if($product->is_sale)
                                                <span class="badge badge-danger">Sale</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{-- Giả sử Brand model relation --}}
                                        {{ optional($product->brand)->name ?? '—' }}
                                    </td>
                                    <td>{{ number_format($product->price,0,',','.') }} đ</td>
                                    <td>
                                        @if($product->sale_price)
                                            {{ number_format($product->sale_price,0,',','.') }} đ
                                            @if($product->sale_price_start_at && $product->sale_price_end_at)
                                                <div class="small text-muted">
                                                    {{ $product->sale_price_start_at->format('d/m/Y') }}—
                                                    {{ $product->sale_price_end_at->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->stock }}</td>
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
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="m-3 text-center text-muted">Không có sản phẩm nào trong danh mục này.</p>
                @endif
            </div>
        </div>

        {{-- Nút quay lại --}}
        <div class="mt-4">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

    </div>
</div>
@endsection
