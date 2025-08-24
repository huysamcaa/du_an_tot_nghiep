@extends('admin.layouts.app')

@section('content')

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Bài viết</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

            <div class="mb-3">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Thêm bài viết
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Danh sách bài viết</strong>
                </div>
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Dropdown chọn số bản ghi hiển thị --}}
                        <form method="GET" action="{{ route('admin.blogs.index') }}" class="d-flex align-items-center" style="gap: 12px;">
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
                        <form method="GET" action="{{ route('admin.blogs.index') }}" class="w-50">
                            <div class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Tìm tiêu đề bài viết..." value="{{ request('search') }}">
                                <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                                @if (request('search'))
                                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <table id="blogs-table" class="table table-bordered">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Danh mục</th> {{-- thêm cột danh mục --}}
            <th>Ảnh</th>
            <th style="width: 15%;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($blogs as $blog)
            <tr>
                <td>{{ $blog->title }}</td>
                <td>
                    {{ $blog->category?->name ?? 'Chưa có' }}
                </td>
                <td>
                    @if ($blog->image)
                        <img src="{{ asset('storage/' . $blog->image) }}" width="100" style="object-fit: cover;">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa bài viết này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Chưa có bài viết nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>


                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $blogs->firstItem() ?? 0 }} đến {{ $blogs->lastItem() ?? 0 }} trên tổng số {{ $blogs->total() }} bài viết
                        </div>
                        <div>
                            {!! $blogs->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
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
            $('#blogs-table').DataTable({
                "order": [[ 0, "asc" ]], 
                "paging": false,
                "searching": false,
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [1, 2] } 
                ],
                "language": {
                    "emptyTable": "Không có bài viết nào trong bảng",
                    "zeroRecords": "Không tìm thấy bài viết nào phù hợp"
                }
            });
        });
    </script>
@endsection



