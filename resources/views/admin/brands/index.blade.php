@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">Danh Sách Thương Hiệu</h1>

<a href="{{ route('admin.brands.create') }}" class="btn btn-primary mb-4"> Thêm Thương Hiệu</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

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
    {{ $brands->links() }}
</div>
@endsection
