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


                {{-- Bảng bình luận --}}
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách bình luận</strong>
                    </div>
                    <div class="card-body">

                        {{-- Bộ lọc --}}
                        <div class="mb-3 d-flex justify-content-between flex-wrap" style="gap: 12px;">
                            <form method="GET" action="{{ route('admin.comments.index') }}" class="d-flex align-items-center" style="gap: 8px;">
                                <label for="per_page" class="mb-0 fw-bold">Hiển thị:</label>
                                <select name="per_page" id="per_page" class="form-control" style="width:auto;" onchange="this.form.submit()">
                                    @foreach([10,25,50,100] as $size)
                                    <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </form>

                            <form method="GET" action="{{ route('admin.comments.index') }}" style="max-width:350px;">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên, nội dung,..." value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Tìm</button>
                                </div>
                            </form>
                        </div>
                        <table id="bootstrap-data" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Người dùng</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comments as $comment)
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
                                    <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.comments.toggle', $comment->id) }}"
                                            class="btn btn-sm"
                                            title="{{ $comment->is_active ? 'Ẩn bình luận' : 'Hiển thị bình luận' }}">
                                            @if($comment->is_active)
                                            <i class="fa fa-eye-slash text-danger"></i> {{-- Icon ẩn --}}
                                            @else
                                            <i class="fa fa-eye text-success"></i> {{-- Icon hiển thị --}}
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Phân trang --}}
                        <div class="mt-3">
                            {{ $comments->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- DataTables --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bootstrap-data').DataTable({
            order: [
                [0, 'desc']
            ],
            paging: false,
            searching: false,
            info: false,
            language: {
                emptyTable: "Chưa có bình luận nào."
            }
        });
    });
</script>
@endsection
