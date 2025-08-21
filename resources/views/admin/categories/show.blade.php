@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục')

@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết danh mục</h4>
            <h6>Thông tin chi tiết về danh mục sản phẩm</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
                <i class="fa fa-edit me-1"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Cột trái: Thông tin chính của danh mục --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Thông tin cơ bản</h5>
                    <div class="row g-3">
                        {{-- Tên danh mục --}}
                        <div class="col-12">
                            <label class="form-label text-muted">Tên danh mục</label>
                            <p class="form-control-static fw-bold fs-5">{{ $category->name }}</p>
                        </div>
                        {{-- ID và Slug --}}
                        <div class="col-md-6">
                            <label class="form-label text-muted">ID</label>
                            <p class="form-control-static">#{{ $category->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Slug</label>
                            <p class="form-control-static">{{ $category->slug }}</p>
                        </div>
                        {{-- Danh mục cha và Icon --}}
                        <div class="col-md-6">
                            <label class="form-label text-muted">Danh mục cha</label>
                            <p class="form-control-static">{{ $category->parent ? $category->parent->name : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Icon</label>
                            <p class="form-control-static">{!! $category->icon !!}</p>
                        </div>
                        {{-- Thứ tự và Trạng thái --}}
                        <div class="col-md-6">
                            <label class="form-label text-muted">Thứ tự hiển thị</label>
                            <p class="form-control-static">{{ $category->ordinal }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Trạng thái</label>
                            <p class="form-control-static">
                                @if($category->is_active)
                                    <span class="badge badge-pill bg-success">Hiển thị</span>
                                @else
                                    <span class="badge badge-pill bg-secondary">Ẩn</span>
                                @endif
                            </p>
                        </div>
                        {{-- Ngày tạo/cập nhật --}}
                        <div class="col-12">
                            <label class="form-label text-muted">Thời gian</label>
                            <p class="form-control-static">
                                Đã tạo: {{ $category->created_at->format('d/m/Y H:i') }} <br>
                                Cập nhật: {{ $category->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cột phải: Sản phẩm liên quan --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Sản phẩm liên quan ({{ $products->count() }})</h5>
                    @if($products->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên</th>
                                        <th>Thương hiệu</th>
                                        <th class="text-end">Giá</th>
                                        <th class="text-end">Tồn kho</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td class="align-middle">
                                            @if($product->thumbnail)
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="thumb" class="rounded" style="width:60px; height:60px; object-fit:cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded text-muted" style="width:60px; height:60px;"><i class="fa fa-image"></i></div>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            {{ $product->name }}
                                            <div class="small mt-1">
                                                @if($product->is_featured) <span class="badge rounded-pill bg-primary">Nổi bật</span> @endif
                                                @if($product->is_trending) <span class="badge rounded-pill bg-warning text-dark">Xu hướng</span> @endif
                                                @if($product->is_sale) <span class="badge rounded-pill bg-danger">Sale</span> @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ optional($product->brand)->name ?? '—' }}</td>
                                        <td class="align-middle text-end">
                                            <div class="text-decoration-line-through text-muted">{{ number_format($product->price,0,',','.') }} đ</div>
                                            @if($product->sale_price)
                                                <div class="text-danger fw-bold">{{ number_format($product->sale_price,0,',','.') }} đ</div>
                                            @endif
                                        </td>
                                        <td class="align-middle text-end">{{ $product->stock }}</td>
                                        <td class="align-middle">
                                            @if($product->is_active)
                                                <span class="badge badge-pill bg-success">Hiển thị</span>
                                            @else
                                                <span class="badge badge-pill bg-secondary">Ẩn</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">Không có sản phẩm nào trong danh mục này.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

