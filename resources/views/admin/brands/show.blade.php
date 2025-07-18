@extends('admin.layouts.app')

@section('content')

<h1 class="mb-4">Chi Tiết Thương Hiệu</h1>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light px-3 py-2 rounded">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Thương hiệu</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $brand->name }}</li>
    </ol>
</nav>

<div class="card shadow-sm p-4">

    <table class="table table-bordered table-striped mb-0">
        <tbody>
            <tr>
                <th width="20%">Tên thương hiệu</th>
                <td>{{ $brand->name }}</td>
            </tr>
            <tr>
                <th>Slug</th>
                <td>{{ $brand->slug }}</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td>
                    <span class="badge badge-{{ $brand->is_active ? 'success' : 'secondary' }}">
                        {{ $brand->is_active ? 'Hiển thị' : 'Ẩn' }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Tổng sản phẩm</th>
                <td>{{ $brand->products_count ?? $brand->products()->count() }}</td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Ngày sửa</th>
                <td>
                    @if ($brand->updated_at != $brand->created_at)
                        {{ $brand->updated_at->format('d/m/Y H:i') }}
                    @else
                        <span class="text-muted">--</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Logo</th>
                <td>
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" width="100" class="img-thumbnail" alt="Logo thương hiệu">
                    @else
                        <span class="text-muted">Không có logo</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mt-4 text-end">
        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-warning me-2">
            Sửa
        </a>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
            Quay lại
        </a>
    </div>

</div>

@endsection
