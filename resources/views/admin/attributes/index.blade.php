@extends('admin.layouts.app')

@section('content')

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Thuộc tính</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                <li class="active">Thuộc tính</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="col-md-12">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong class="card-title">Danh sách thuộc tính</strong>
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Thêm thuộc tính
                    </a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Bộ lọc số lượng hiển thị --}}
                        <form method="GET" action="{{ route('admin.attributes.index') }}" class="d-flex align-items-center" style="gap: 12px;">
                            <div>
                                <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                                <select name="perPage" id="perPage" class="form-control d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                    <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </form>

                        {{-- Ô tìm kiếm --}}
                        <form method="GET" action="{{ route('admin.attributes.index') }}" class="w-50">
                            <div class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Tìm tên, slug..." value="{{ request('search') }}">
                                <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                                @if (request('search'))
                                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <table id="attribute-table" class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width:5%;">ID</th>
                                <th>Tên</th>
                                <th>Slug</th>
                                <th>Biến thể?</th>
                                <th>Hiển thị?</th>
                                <th style="width:12%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attributes as $attr)
                                <tr>
                                    <td>{{ $attr->id }}</td>
                                    <td>{{ $attr->name }}</td>
                                    <td>{{ $attr->slug }}</td>
                                    <td>
                                        @if($attr->is_variant)
                                            <span class="badge badge-success">✔</span>
                                        @else
                                            <span class="badge badge-danger">✘</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attr->is_active)
                                            <span class="badge badge-success">✔</span>
                                        @else
                                            <span class="badge badge-danger">✘</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.attributes.edit', $attr) }}"
                                            class="btn btn-sm btn-outline-warning" title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.attributes.destroy', $attr) }}"
                                            method="POST" style="display:inline-block;"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa thuộc tính này?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có thuộc tính nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Phân trang + Thống kê --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $attributes->firstItem() ?? 0 }} đến {{ $attributes->lastItem() ?? 0 }} trên tổng số {{ $attributes->total() }} thuộc tính
                        </div>
                        <div>
                            {!! $attributes->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    {{-- jQuery and DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#attribute-table').DataTable({
                "order": [[0, "desc"]],
                "paging": false,
                "searching": false,
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [5] }
                ],
                "language": {
                    "emptyTable": "Không có thuộc tính nào",
                    "zeroRecords": "Không tìm thấy thuộc tính phù hợp"
                }
            });
        });
    </script>
@endsection
