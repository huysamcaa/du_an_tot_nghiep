@extends('admin.layouts.app')

@section('content')

    {{-- Breadcrumb --}}
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Bình luận</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                <li class="active">Bình luận</li>
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Card --}}
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Danh sách bình luận</strong>
                </div>
                <div class="card-body">

                    {{-- Bộ lọc --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form method="GET" action="{{ route('admin.comments.index') }}" class="d-flex align-items-center" style="gap: 12px;">
                            <div>
                                <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                                <select name="per_page" id="per_page" class="form-control d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                    @foreach([10,25,50,100] as $size)
                                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        <form method="GET" action="{{ route('admin.comments.index') }}" class="w-50">
                            <div class="d-flex">
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm sản phẩm, người dùng, nội dung..." value="{{ request('keyword') }}">
                                <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                                @if (request('keyword'))
                                    <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Bảng --}}
                    <table id="comment-table" class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width:5%;">ID</th>
                                <th>Sản phẩm</th>
                                <th>Người dùng</th>
                                <th>Nội dung</th>
                                <th>Trạng thái</th>
                                <th style="width:12%;">Ngày tạo</th>
                                <th style="width:8%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comments as $comment)
                                <tr>
                                    <td>{{ $comment->id }}</td>
                                    <td>{{ $comment->product->name ?? '[Sản phẩm đã xóa]' }}</td>
                                    <td>{{ $comment->user->name ?? '[Người dùng không tồn tại]' }}</td>
                                    <td class="text-start">{{ $comment->content }}</td>
                                    <td>
                                        @if($comment->is_active)
                                            <span class="badge badge-success">✔ Hiển thị</span>
                                        @else
                                            <span class="badge badge-danger">✘ Ẩn</span>
                                        @endif
                                    </td>
                                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.comments.toggle', $comment->id) }}" class="btn btn-sm btn-outline-primary" title="{{ $comment->is_active ? 'Ẩn bình luận' : 'Hiển thị bình luận' }}">
                                            @if($comment->is_active)
                                                <i class="fa fa-eye-slash text-danger"></i>
                                            @else
                                                <i class="fa fa-eye text-success"></i>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không có bình luận nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $comments->firstItem() ?? 0 }} đến {{ $comments->lastItem() ?? 0 }} trên tổng số {{ $comments->total() }} bình luận
                        </div>
                        <div>
                            {!! $comments->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
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
            $('#comment-table').DataTable({
                "order": [[ 5, "desc" ]],
                "paging": false,
                "searching": false,
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [3,4,6] }
                ],
                "language": {
                    "emptyTable": "Không có bình luận nào trong bảng",
                    "zeroRecords": "Không tìm thấy bình luận nào phù hợp"
                }
            });
        });
    </script>
@endsection
