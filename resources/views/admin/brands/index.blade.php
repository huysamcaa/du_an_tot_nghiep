@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Thương hiệu</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="active">Thương hiệu</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Nội dung chính --}}
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Nút Thêm & Đã xóa --}}
                <div class="mb-3 d-flex" style="gap: 10px;">
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Thêm thương hiệu
                    </a>
                    <a href="{{ route('admin.brands.trash') }}" class="btn btn-secondary">
                        <i class="fa fa-trash"></i> Thương hiệu đã xóa
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách thương hiệu</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên</th>
                                    <th>Slug</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày sửa</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $brand)
                                <tr>
                                    <td>
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/' . $brand->logo) }}" width="60" class="img-thumbnail" alt="Logo">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->slug }}</td>
                                    <td>
                                        @if($brand->is_active)
                                            <span class="badge badge-success">✔</span>
                                        @else
                                            <span class="badge badge-secondary">✘</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($brand->updated_at != $brand->created_at)
                                            {{ $brand->updated_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted"></span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-sm btn-outline-info" title="Xem">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa thương hiệu này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Chưa có thương hiệu nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Phân trang --}}
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Hiển thị từ {{ $brands->firstItem() ?? 0 }} đến {{ $brands->lastItem() ?? 0 }} trên tổng số {{ $brands->total() }} thương hiệu
                            </div>
                            <div>
                                {{ $brands->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>

                    </div><!-- card-body -->
                </div><!-- card -->

            </div><!-- col -->
        </div><!-- row -->
    </div><!-- animated -->
</div><!-- content -->

{{-- DataTables --}}
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
