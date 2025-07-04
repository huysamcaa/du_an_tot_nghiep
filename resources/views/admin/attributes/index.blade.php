@extends('admin.layouts.app')
@section('content')
<h2>Danh sách thuộc tính</h2>
<a href="{{ route('admin.attributes.create') }}" class="btn btn-success mb-2">Thêm thuộc tính</a>
@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th><th>Tên</th><th>Slug</th><th>Biến thể?</th><th>Hiển thị?</th><th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attributes as $attr)
        <tr>
            <td>{{ $attr->id }}</td>
            <td>{{ $attr->name }}</td>
            <td>{{ $attr->slug }}</td>
            <td>{{ $attr->is_variant ? '✔' : '' }}</td>
            <td>{{ $attr->is_active ? '✔' : '' }}</td>
           <td>
    <a href="{{ route('admin.attributes.edit', $attr) }}" class="btn btn-warning btn-sm icon-btn" title="Sửa">
        <i class="fa fa-pencil"></i>
    </a>
    <form action="{{ route('admin.attributes.destroy', $attr) }}" method="POST" style="display:inline-block">
        @csrf @method('DELETE')
        <button onclick="return confirm('Xóa?')" class="btn btn-danger btn-sm icon-btn" title="Xóa">
            <i class="fa fa-trash"></i>
        </button>
    </form>
</td>

        </tr>
        @endforeach
    </tbody>
</table>
@endsection