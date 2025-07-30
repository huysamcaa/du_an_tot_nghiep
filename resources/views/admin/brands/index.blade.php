@extends('admin.layouts.app')

@section('content')
<h1>Danh sách trạng thái đơn hàng</h1>
<br>
 <div>
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">Thêm Thương Hiệu</a>
                                <a href="{{ route('admin.brands.trash') }}" class="btn btn-danger">Thương Hiệu Đã Xóa</a>
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
                            <li><a href="{{ route('admin.order_statuses.index') }}">Danh Sách Thương Hiệu</a></li>
                            <li class="active">Danh Sách Thương Hiệu</li>
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
                        <strong class="card-title">Quản lý Thương hiệu</strong>
                    </div>
                    <div class="card-body">
                       

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="mb-3 d-flex justify-content-between">
                           
                            
                            <form method="GET" action="{{ route('admin.brands.index') }}" class="d-flex" style="gap: 12px; align-items: center;">
                                <div>
                                    <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                                    <select name="perPage" id="per_page" class="form-control d-inline-block" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                                        <option value="10" {{ request('perPage', 10) == '10' ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </form>
                            
                            <form method="GET" action="{{ route('admin.brands.index') }}" style="max-width:350px;">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thương hiệu..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </form>
                        </div>

                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
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
                                @foreach($brands as $brand)
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
                                            <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-sm btn-info me-1">Chi tiết</a>
                                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-warning me-1">Sửa</a>
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
                        <div class="d-flex justify-content-center mt-3">
                            {{ $brands->links('pagination::simple-bootstrap-4') }}
                        </div>
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
            order: [[0, 'desc']],
            paging: false, // Disable DataTables pagination since we're using Laravel pagination
            searching: false // Disable DataTables search since we have our own
        });
    });
</script>
@endsection