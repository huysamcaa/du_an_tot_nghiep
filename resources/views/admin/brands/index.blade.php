@extends('admin.layouts.app')

@section('content')

    <h1 class="mb-4">Danh Sách Thương Hiệu</h1>

    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary mb-4">Thêm Thương Hiệu</a>
    <a href="{{ route('admin.brands.trash') }}" class="btn btn-secondary mb-4">Thương Hiệu Đã Xóa</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Thanh Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item d-flex justify-content-between w-100">
                <span>Admin</span>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Trang chủ</a>
                    <a href="{{ route('admin.brands.index') }}" class="breadcrumb-item">Thương hiệu</a>
                    <span class="breadcrumb-item active">Danh Sách Thương hiệu</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Tạo phần "Show entries" và tìm kiếm -->
   <form method="GET" action="{{ route('admin.brands.index') }}" class="row g-2 align-items-center mb-4">
    {{-- Số lượng hiển thị --}}
    <div class="col-auto">
        <label for="entries" class="form-label mb-0">Hiển thị</label>
        <select name="perPage" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="10" {{ request('perPage', 10) == '10' ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
        </select>
    </div>


</form>
<form method="GET" action="{{ route('admin.brands.index') }}" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        @if(request('search'))
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-times me-1"></i> Xóa
            </a>
        @endif
    </div>
</form>


    <!-- Bảng Danh Sách Thương Hiệu -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên Thương Hiệu</th>
                <th>Slug</th>
                <th>Logo</th>
                <th>Trạng Thái</th>
                <th>Ngày Tạo</th>
                <th>Ngày Sửa</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $index => $brand)
                <tr>
                    <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->slug }}</td>
                    <td>
                        @if ($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" width="60" alt="Logo">
                        @else
                            <span class="text-muted">--</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }}">
    <i class="fa-solid {{ $brand->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
    {{ $brand->is_active ? 'Hiển thị' : 'Ẩn' }}
</span>

                    </td>
                    <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($brand->updated_at != $brand->created_at)
                            {{ $brand->updated_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">--</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-sm btn-info me-1">Chi
                            tiết</a>
                        <a href="{{ route('admin.brands.edit', ['brand' => $brand->id]) }}"
                            class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.brands.destroy', ['brand' => $brand->id]) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Bạn có chắc muốn xóa không?')">Xóa</button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
        Hiển thị từ {{ $brands->firstItem() }} đến {{ $brands->lastItem() }} trong tổng số {{ $brands->total() }} thương hiệu
    </div>

    <div>
        @if ($brands->hasPages())
            {{ $brands->links('pagination::bootstrap-4') }}
        @else
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item active"><span class="page-link">1</span></li>
                </ul>
            </nav>
        @endif
    </div>
</div>

<div class="text-muted small mt-2">
    Trang {{ $brands->currentPage() }} / {{ $brands->lastPage() }} | Tổng: {{ $brands->total() }} mục
</div>

@endsection
