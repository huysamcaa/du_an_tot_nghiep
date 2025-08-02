@extends('admin.layouts.app')

@section('content')
<h1>Danh sách phản hồi bình luận</h1>
@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách phản hồi bình luận</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data" class="table table-striped table-bordered">
                            <form method="GET" action="{{ route('admin.comments.index') }}" class="mb-3 d-flex" style="gap: 12px; align-items: center;">
                                <div>
                                    <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                                    <select name="per_page" id="per_page" class="form-control d-inline-block" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                                            <option value="1" >10</option>
                                            <option value="2" >25</option>
                                            <option value="3" >50</option>
                                            <option value="4" >100</option>
                                    </select>
                                    <span></span>
                                </div>

                            </form>
                            <form method="GET" action="{{ route('admin.replies.index') }}" class="mb-3" style="max-width:350px;">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên, nội dung,..." value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </form>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Bình luận gốc</th>
                                    <th>Người trả lời</th>
                                    <th>Người được trả lời</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($replies as $reply)
                                <tr>
                                    <td>{{ $reply->id }}</td>
                                    <td>{{ $reply->comment->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                                    <td>{{ $reply->comment->content ?? 'Không tìm thấy bình luận' }}</td>
                                    <td>{{ $reply->user->name ?? 'Người dùng không tồn tại'}}</td>
                                    <td>{{ $reply->replyUser->name ?? 'Người dùng không tồn tại' }}</td>
                                    <td>{{ $reply->content }}</td>
                                    <td>
                                        {{ $reply->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </td>
                                    <td>
                                        <!-- Sử dụng route toggleVisibility cho CommentReply -->
                                        <a href="{{ route('admin.replies.toggle', $reply->id) }}" class="btn btn-sm btn-{{ $reply->is_active ? 'danger' : 'success' }}">
                                            {{ $reply->is_active ? 'Ẩn' : 'Hiển thị' }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $replies->appends(request()->query())->links() }}
                        </div>
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
