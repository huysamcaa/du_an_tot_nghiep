@extends('admin.layouts.app')

@section('content')
<h1>Danh Sách Danh Mục</h1>
<br>
                            <div>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm danh mục</a>
                                <a href="{{ route('admin.categories.trashed') }}" class="btn btn-secondary">Danh mục đã xóa</a>
                            </div>
 <div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Admin</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Trang chủ</a></li>
                            <li><a href="{{ route('admin.order_statuses.index') }}">Danh Mục</a></li>
                            <li class="active">Danh Sách Danh Mục</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Quản lý Danh mục</strong>
                    </div>
                    <div class="card-body">
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

                        <div class="mb-3 d-flex justify-content-between">


                            <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex" style="gap: 12px; align-items: center;">
                                <div>
                                    <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                                    <select name="per_page" id="per_page" class="form-control d-inline-block" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                                        @foreach([10, 25, 50, 100] as $size)
                                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>

                            <form method="GET" action="{{ route('admin.categories.index') }}" style="max-width:350px;">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên danh mục..." value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </form>
                        </div>

                        <table id="bootstrap-data" class="table table-striped table-bordered">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#bootstrap-data-table').DataTable({
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
