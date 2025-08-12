@extends('admin.layouts.app')

@section('content')
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Danh mục</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                <li class="active">Danh mục</li>
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Nút thêm và xem danh mục đã xóa --}}
            <div class="mb-3 d-flex" style="gap: 10px;">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Thêm danh mục
                </a>
                <a href="{{ route('admin.categories.trashed') }}" class="btn btn-secondary">
                    <i class="fa fa-trash"></i> Danh mục đã xóa
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Danh sách danh mục</strong>
                </div>
                <div class="card-body">
                    {{-- Bộ lọc --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex align-items-center" style="gap: 12px;">
                            <div>
                                <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                                <select name="perPage" id="perPage" class="form-control" style="width:auto;" onchange="this.form.submit()">
                                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </form>

                        <form method="GET" action="{{ route('admin.categories.index') }}" class="w-50">
                            <div class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên danh mục..." value="{{ request('search') }}">
                                <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                                @if (request('search'))
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Bảng --}}
                    <table id="categories-table" class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Danh mục cha</th>
                                <th>Icon</th>
                                <th>Thứ tự</th>
                                <th>Trạng thái</th>
                                <th style="width: 15%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parent->name ?? '-' }}</td>
                                    <td>{!! $category->icon !!}</td>
                                    <td>{{ $category->ordinal }}</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge badge-success">✔</span>
                                        @else
                                            <span class="badge badge-danger">✘</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-info" title="Xem">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                                    <td colspan="6" class="text-center text-muted">Chưa có danh mục nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $categories->firstItem() ?? 0 }} đến {{ $categories->lastItem() ?? 0 }} trên tổng số {{ $categories->total() }} danh mục
                        </div>
                        <div>
                            {!! $categories->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#categories-table').DataTable({
                "order": [[ 0, "asc" ]],
                "paging": false,
                "searching": false,
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [5] }
                ],
                "language": {
                    "emptyTable": "Không có danh mục nào",
                    "zeroRecords": "Không tìm thấy danh mục phù hợp"
                }
            });
        });
    </script>
@endsection
