@extends('admin.layouts.app')

@section('content')

<h1 class="mb-4">Danh Sách Thương Hiệu</h1>

<a href="{{ route('admin.brands.create') }}" class="btn btn-primary mb-4">Thêm Thương Hiệu</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
<form method="GET" action="{{ route('admin.brands.index') }}" class="d-flex justify-content-between mb-3">
    <div>
        <label for="entries">Show</label>
        <select name="perPage" class="form-control d-inline w-auto" onchange="this.form.submit()">
            <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
        </select>
        entries
    </div>
    <div>
        <label for="search" class="mr-2">Search:</label>
        <input type="text" name="search" class="form-control d-inline w-auto" value="{{ request('search') }}" placeholder="Tìm kiếm">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
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
        @foreach($brands as $index => $brand)
            <tr>
                <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->slug }}</td>
                <td>
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" width="60" alt="Logo">
                    @else
                        <span class="text-muted">--</span>
                    @endif
                </td>
                <td>
                    <span class="badge badge-{{ $brand->is_active ? 'success' : 'secondary' }}">
                        {{ $brand->is_active ? 'Hiển Thị' : 'Ẩn' }}
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
                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa không?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Phân trang -->
<div class="d-flex justify-content-center">
    <!-- Phân trang với nút Previous và Next -->
    {{ $brands->links('pagination::simple-bootstrap-4') }}
</div>

@endsection
