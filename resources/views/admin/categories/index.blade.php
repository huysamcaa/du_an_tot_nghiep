@extends('admin.layouts.app')

@section('content')
<style>
    body, .container {
        background: #ecf5f4 !important;
    }
    .table-custom {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(44, 62, 80, 0.07);
    }
    .table-custom th {
        background: #b2dfdb;
        color: #22577a;
        font-weight: 700;
        border: none;
        text-align: center;
    }
    .table-custom td {
        background: #ecf5f4;
        color: #22577a;
        vertical-align: middle;
        border-top: 1px solid #b2dfdb;
        text-align: center;
    }
    .table-custom tr:hover td {
        background: #b2dfdb;
        color: #22577a;
    }
    .btn-primary {
        background: #38b6ff;
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 6px;
        transition: background 0.2s;
    }
    .btn-primary:hover {
        background: #22577a;
        color: #fff;
    }
    .btn-warning {
        background: #ffd166;
        border: none;
        color: #22577a;
        font-weight: 600;
        border-radius: 6px;
        transition: background 0.2s;
    }
    .btn-warning:hover {
        background: #ffa600;
        color: #fff;
    }
    .btn-danger {
        background: #ef476f;
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 6px;
        transition: background 0.2s;
    }
    .btn-danger:hover {
        background: #d90429;
        color: #fff;
    }
    h1 {
        color: #22577a;
        font-weight: 700;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .alert-success {
        background: #b2dfdb;
        color: #22577a;
        border: none;
        border-radius: 6px;
    }
</style>
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