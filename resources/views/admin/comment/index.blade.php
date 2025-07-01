@extends('admin.layouts.app')

@section('content')
<h1>Danh sách bình luận</h1>
@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách bình luận</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên sản phẩm</th>
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
                                    <td>{{ $comment->product->name }}</td>
                                    <td>{{ $comment->user->name }}</td>
                                    <td>{{ $comment->content }}</td>
                                    <td>
                                        {{ $comment->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </td>
                                    <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <!-- Sử dụng route toggleVisibility -->
                                        <a href="{{ route('admin.comments.toggle', $comment->id) }}" class="btn btn-sm btn-{{ $comment->is_active ? 'danger' : 'success' }}">
                                            {{ $comment->is_active ? 'Ẩn' : 'Hiển thị' }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
      $(document).ready(function() {
    $('#bootstrap-data-table').DataTable({
        order: [[0, 'desc']] // Sắp xếp cột 9 - ngày tạo giảm dần
    });
});
</script>
@endsection