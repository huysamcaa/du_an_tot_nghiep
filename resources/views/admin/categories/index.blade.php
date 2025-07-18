@extends('admin.layouts.app')

@section('content')

<div class="container">
    <h1>Quản lý Danh mục</h1>
    <br>
   <br>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Thêm danh mục</a>
    <a href="{{ route('admin.categories.trashed') }}" class="btn btn-secondary mb-3">Danh mục đã xóa</a>

    <table id="bootstrap-data-table" class="table table-striped table-bordered">
       
       
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3 d-flex" style="gap: 12px; align-items: center;">
                        <div>
                            <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                            <select name="per_page" id="per_page" class="form-control d-inline-block" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                                @foreach([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            <span></span>
                        </div>
                        
                    </form>
               
            
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3" style="max-width:350px;">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên danh mục..." value="{{ request('keyword') }}">
                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                        </div>
                    </form>
               
        <thead>
            <tr>
                
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
                    
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->parent->name ?? '-' }}</td>
                    <td>{!! $category->icon !!}</td>
                    <td>{{ $category->ordinal }}</td>
                    <td>{{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td>
                        <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-info btn-sm">Xem</a>
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
