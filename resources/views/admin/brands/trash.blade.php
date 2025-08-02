@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">Thương Hiệu Đã Xóa</h1>

<a href="{{ route('admin.brands.index') }}" class="btn btn-secondary mb-3">← Quay lại danh sách</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên thương hiệu</th>
            <th>Slug</th>
            <th>Số sản phẩm</th>
            <th>Ngày xóa</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($brands as $index => $brand)
            <tr>
                <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->slug }}</td>
                <td>{{ $brand->products_count }}</td>
                <td>
                    @if($brand->deleted_at)
                        {{ $brand->deleted_at->format('d/m/Y H:i') }}
                    @else
                        <span class="text-muted">--</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.brands.restore', $brand->id) }}" method="POST" onsubmit="return confirm('Khôi phục thương hiệu này?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Khôi phục</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Không có thương hiệu nào đã bị xóa.</td>
            </tr>
        @endforelse
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

@endsection
