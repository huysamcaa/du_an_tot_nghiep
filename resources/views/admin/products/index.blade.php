@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Danh sách sản phẩm</h4>
            <h6>Xem / Tìm kiếm / Lọc sản phẩm</h6>
        </div>
        <div class="page-btn d-flex" style="gap: 10px;">
            <a href="{{ route('admin.products.create') }}" class="btn btn-added">
                <img src="{{ asset('assets/admin/img/icons/plus.svg') }}" class="me-2" alt="img"> Thêm sản phẩm
            </a>
            <a href="{{ route('admin.products.trashed') }}" class="btn btn-secondary">
             Sản phẩm đã xóa
            </a>
        </div>
    </div>

    {{-- Thông báo thành công --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">

            {{-- Form tìm kiếm & lọc --}}
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 align-items-center mb-3">

                {{-- Tìm kiếm --}}
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Tìm tên, danh mục, thương hiệu..."
                        value="{{ request('search') }}">
                </div>

                {{-- Danh mục --}}
                <div class="col-md-2">
                    <select name="category_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Thương hiệu --}}
                <div class="col-md-2">
                    <select name="brand_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Tất cả thương hiệu</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Số lượng hiển thị --}}
                <div class="col-md-1">
                    <select name="perPage" class="form-control" onchange="this.form.submit()">
                        @foreach([10,25,50,100] as $size)
                        <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nút tìm kiếm & xóa lọc --}}
                <div class="col-md-2 d-flex" style="gap: 6px;">
                    <button class="btn btn-primary" type="submit"> Tìm kiếm</button>
                    @if (request()->hasAny(['search','category_id','brand_id','perPage']))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Xóa</a>
                    @endif
                </div>
            </form>

            {{-- Bảng sản phẩm --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Số lượng</th>
                            <th>Tổng giá</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td>
                                <img src="{{ $product->thumbnail
                                ? asset('storage/' . $product->thumbnail)
                                : asset('assets/admin/img/product/no-image.png') }}"
                                    width="60" alt="Ảnh sản phẩm">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>{{ $product->total_stock ?? 0 }}</td>
                            <td class="text-end">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center" style="gap: 10px;">
                                    <a href="{{ route('admin.products.show', $product->id) }}" title="Xem">
                                        <img src="{{ asset('assets/admin/img/icons/eye.svg') }}" alt="Xem">
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" title="Sửa">
                                        <img src="{{ asset('assets/admin/img/icons/edit.svg') }}" alt="Sửa">
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0" title="Xóa">
                                            <img src="{{ asset('assets/admin/img/icons/delete.svg') }}" alt="Xóa">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Chưa có sản phẩm nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>



            {{-- Phân trang --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Hiển thị từ {{ $products->firstItem() ?? 0 }} đến {{ $products->lastItem() ?? 0 }} trên tổng số {{ $products->total() }} sản phẩm
                </div>
                <div>
                    {!! $products->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
