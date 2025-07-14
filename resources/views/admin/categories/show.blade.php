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
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td class="text-right">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-success">Hiển thị</span>
                                    @else
                                        <span class="badge badge-secondary">Ẩn</span>
                                    @endif
                                </td>
                                <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
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