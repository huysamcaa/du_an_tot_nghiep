@extends('admin.layouts.app')

@section('content')

<div class="container">
    <h1>Quản lý Danh mục</h1>
    <br>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Thêm danh mục</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Danh mục cha</th>
                <th>Icon</th>
                <th>Thứ tự</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->parent->name ?? '-' }}</td>
                    <td>{!! $category->icon !!}</td>
                    <td>{{ $category->ordinal }}</td>
                    <td>{{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
