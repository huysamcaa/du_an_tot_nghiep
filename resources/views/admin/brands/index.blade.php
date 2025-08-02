@extends('admin.layouts.app')

@section('content')
<h1>Danh sách thương hiệu</h1>
<a href="{{ route('admin.brands.create') }}" class="btn btn-primary mb-3">Thêm thương hiệu</a>
<a href="{{ route('admin.brands.trash') }}" class="btn btn-secondary mb-3">Thương hiệu đã xóa</a>

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title"><h1>Admin</h1></div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Trang chủ</a></li>
                            <li><a href="#">Thương hiệu</a></li>
                            <li class="active">Danh sách thương hiệu</li>
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
                        <strong class="card-title">Danh sách thương hiệu</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên</th>
                                    <th>Slug</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày sửa</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brands as $brand)
                                <tr>
                                    <td>
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}" width="60" alt="Logo">
                                        @else
                                            <span>Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->slug }}</td>
                                    <td>
                                        <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            <i class="fa-solid {{ $brand->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            {{ $brand->is_active ? 'Hiển thị' : 'Ẩn' }}
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
                                        <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa thương hiệu này?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Phân trang -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Hiển thị từ {{ $brands->firstItem() ?? 0 }} đến {{ $brands->lastItem() ?? 0 }} trên tổng số {{ $brands->total() }} thương hiệu
                            </div>
                            <div>
                                {{ $brands->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#bootstrap-data').DataTable({
            order: [[1, 'asc']],
            paging: false,
            searching: false
        });
    });
</script>
@endsection
