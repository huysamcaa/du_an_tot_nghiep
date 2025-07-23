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
                        <table id="bootstrap-data" class="table table-striped table-bordered">
                            <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3 d-flex" style="gap: 12px; align-items: center;">
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
               
            
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3" style="max-width:350px;">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên danh mục..." value="{{ request('keyword') }}">
                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                        </div>
                    </form>
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
                                    <td>{{ $comment->product->name ?? '[Sản phẩm đã bị xóa]' }}</td>
                                    <td>{{ $comment->user->name ?? '[Người dùng không tồn tại]' }}</td>
                                    <td>{{ $comment->content }}</td>
                                    <td>
                                        {{ $comment->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </td>
                                    <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <!-- Sử dụng route toggleVisibility -->
                                        <a href="{{ route('admin.comments.toggle', $comment->id) }}" class="btn btn-sm btn-{{ $comment->is_active ? 'primary' : 'success' }}">
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