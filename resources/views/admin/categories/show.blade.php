@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Chi tiết danh mục</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $category->id }}</td>
        </tr>
        <tr>
            <th>Tên danh mục</th>
            <td>{{ $category->name }}</td>
        </tr>
        <tr>
            <th>Slug</th>
            <td>{{ $category->slug }}</td>
        </tr>
        <tr>
            <th>Danh mục cha</th>
            <td>{{ $category->parent ? $category->parent->name : 'Không có' }}</td>
        </tr>
        <tr>
            <th>Icon</th>
            <td>{!! $category->icon !!}</td>
        </tr>
        <tr>
            <th>Thứ tự</th>
            <td>{{ $category->ordinal }}</td>
        </tr>
        <tr>
            <th>Trạng thái</th>
            <td>
                @if($category->is_active)
                    <span class="badge badge-success">Hiển thị</span>
                @else
                    <span class="badge badge-secondary">Ẩn</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Ngày tạo</th>
            <td>{{ $category->created_at }}</td>
        </tr>
        <tr>
            <th>Ngày cập nhật</th>
            <td>{{ $category->updated_at }}</td>
        </tr>
    </table>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection