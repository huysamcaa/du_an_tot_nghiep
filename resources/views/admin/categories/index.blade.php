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
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Nút thêm và Danh mục đã xóa --}}
                <div class="mb-3 d-flex" style="gap: 10px;">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-success" title="Thêm danh mục">
                        <i class="fa fa-plus"></i> Thêm danh mục
                    </a>
                    <a href="{{ route('admin.categories.trashed') }}" class="btn btn-secondary" title="Danh mục đã xóa">
                        <i class="fa fa-trash"></i> Danh mục đã xóa
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách danh mục</strong>
                    </div>
                    <div class="card-body">

                        {{-- Bộ lọc: per page & tìm kiếm --}}
                        <div class="mb-3 d-flex justify-content-between flex-wrap" style="gap: 12px;">
                            <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex align-items-center" style="gap: 8px;">
                                <label for="per_page" class="mb-0 fw-bold">Hiển thị:</label>
                                <select name="per_page" id="per_page" class="form-control" style="width:auto;" onchange="this.form.submit()">
                                    @foreach([10,25,50,100] as $size)
                                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <form method="GET" action="{{ route('admin.categories.index') }}" style="max-width:350px;">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm..." value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Tìm</button>
                                </div>
                            </form>
                        </div>

                        {{-- Bảng danh sách --}}
                        <table id="bootstraps-data" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Danh mục cha</th>
                                    <th>Icon</th>
                                    <th>Thứ tự</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
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
                                            <a href="{{ route('admin.categories.show', $category->id) }}"
                                               class="btn btn-sm btn-outline-info" title="Xem">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                               class="btn btn-sm btn-outline-warning" title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                  method="POST"
                                                  style="display:inline-block;"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Xóa">
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
                        <div class="mt-3">
                            {{ $categories->withQueryString()->links() }}
                        </div>

                    </div><!-- .card-body -->
                </div><!-- .card -->

            </div><!-- .col -->
        </div><!-- .row -->
    </div><!-- .animated -->
</div><!-- .content -->

{{-- DataTables --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bootstrap-data').DataTable({
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
