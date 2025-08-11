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

<div class="content col-md-12">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Nút thêm và xem đã xóa --}}
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                {{-- Form chọn số bản ghi --}}
                <form method="GET" action="{{ route('admin.brands.index') }}" class="d-flex align-items-center" style="gap: 12px;">
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

                {{-- Form tìm kiếm --}}
                <form method="GET" action="{{ route('admin.brands.index') }}" class="w-50">
                    <div class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Tìm tên hoặc slug thương hiệu..." value="{{ request('search') }}">
                        <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                        @if (request('search'))
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                        @endif
                    </div>
                </form>
            </div>

            <table id="brand-table" class="table table-striped table-bordered text-center align-middle">
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
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Hiển thị từ {{ $brands->firstItem() ?? 0 }} đến {{ $brands->lastItem() ?? 0 }} trên tổng số {{ $brands->total() }} thương hiệu
                </div>
                <div>
                    {!! $brands->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
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
            $('#brand-table').DataTable({
                "order": [[ 1, "asc" ]],
                "paging": false,
                "searching": false,
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] }
                ],
                "language": {
                    "emptyTable": "Không có thương hiệu nào trong bảng",
                    "zeroRecords": "Không tìm thấy thương hiệu nào phù hợp"
                }
            });
        });
    </script>
@endsection
