@extends('admin.layouts.app')

@section('content')

    {{-- Breadcrumbs --}}
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Trạng thái đơn hàng</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                <li class="active">Trạng thái đơn hàng</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Nội dung --}}
    <div class="content">
        <div class="col-md-12">

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Nút thêm trạng thái --}}
            <div class="mb-3">
                <a href="{{ route('admin.order_statuses.create') }}" class="btn btn-success" title="Thêm trạng thái đơn hàng">
                    <i class="fa fa-plus"></i> Thêm trạng thái đơn hàng
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Danh sách trạng thái đơn hàng</strong>
                </div>
                <div class="card-body">

                    {{-- Bộ lọc hiển thị số lượng + tìm kiếm --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form method="GET" action="{{ route('admin.order_statuses.index') }}" class="d-flex align-items-center" style="gap: 12px;">
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

                        <form method="GET" action="{{ route('admin.order_statuses.index') }}" class="w-50">
                            <div class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Tìm tên trạng thái..." value="{{ request('search') }}">
                                <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                                @if (request('search'))
                                    <a href="{{ route('admin.order_statuses.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Bảng --}}
                    <table id="status-table" class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th>Tên trạng thái</th>
                                <th style="width: 15%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($statuses as $status)
                                <tr>
                                    <td>{{ $status->id }}</td>
                                    <td>{{ $status->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.order_statuses.edit', $status->id) }}" class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.order_statuses.destroy', $status->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')" title="Xóa">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted">Không có trạng thái nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $statuses->firstItem() ?? 0 }} đến {{ $statuses->lastItem() ?? 0 }} trên
                            tổng số {{ $statuses->total() }} trạng thái
                        </div>
                        <div>
                            {!! $statuses->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    

    <script>
        $(document).ready(function() {
            $('#status-table').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "order": [[0, "asc"]],
                "columnDefs": [
                    { "orderable": false, "targets": [2] }
                ]
            });
        });
    </script>
@endsection
