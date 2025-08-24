@extends('admin.layouts.app')

@section('content')
{{-- Nội dung chính --}}
<div class="content">
    <div class="page-header float-left">
        <div class="page-title">
            <h4>Phản hồi bình luận</h4>
            <small class="text-muted">Danh sách phản hồi bình luận</small>
        </div>
    </div>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">

                {{-- Thông báo --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Bảng phản hồi --}}
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách phản hồi bình luận</strong>
                    </div>
                    <div class="card-body">
                        {{-- Bộ lọc --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex">
                        <form method="GET" action="{{ route('admin.replies.index') }}" class="d-flex align-items-center" style="gap: 12px;">
                            <div>
                                <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                                <select name="per_page" id="per_page" class="form-control d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                    @foreach([10,25,50,100] as $size)
                                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                            <div class="d-flex w-75 gap-3">
                                <input type="text" name="keyword" class="form-control" placeholder="Tìm sản phẩm, người dùng, nội dung" value="{{ request('keyword') }}">
                            <div class="w-25">
                        <select name="is_active" class="form-select">
                            <option value="">--Trạng thái--</option>
                            <option value="1" {{request('is_active') === '1' ? 'selected' : ''}}>Hiển thị</option>
                            <option value="0" {{request('is_active') === '0' ? 'selected' : ''}}>Ẩn</option>
                        </select></div>
                                <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                                @if (request('keyword'))
                                    <a href="{{ route('admin.replies.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                                @endif
                            </div>
                        </form>
                    </div>

                        <table id="bootstrap-data" class="table table-bordered table-hover align-middle text-center">
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
                                        <td class="text-start">{{ $reply->comment->content ?? 'Không tìm thấy bình luận' }}</td>
                                        <td>{{ $reply->user->name ?? 'Người dùng không tồn tại'}}</td>
                                        <td>{{ $reply->replyUser->name ?? 'Người dùng không tồn tại' }}</td>
                                        <td class="text-start">{{ $reply->content }}</td>
                                        <td>
                                            @if($reply->is_active)
                                                <span class="badge badge-success text-success">✔ Hiển thị</span>
                                            @else
                                                <span class="badge badge-danger text-danger">✘ Ẩn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.replies.toggle', $reply->id) }}"
                                               class="btn btn-sm"
                                               title="{{ $reply->is_active ? 'Ẩn phản hồi' : 'Hiển thị phản hồi' }}">
                                                @if($reply->is_active)
                                                    <i class="fa fa-eye-slash text-danger"></i>
                                                @else
                                                    <i class="fa fa-eye text-success"></i>
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Phân trang --}}
                        <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $replies->firstItem() ?? 0 }} đến {{ $replies->lastItem() ?? 0 }} trên tổng số {{ $replies->total() }} bình luận
                        </div>
                        <div>
                            {!! $replies->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- DataTables --}}

<script>
    $(document).ready(function() {
        $('#bootstrap-data').DataTable({
            order: [[0, 'desc']],
            paging: false,
            searching: false,
            info: false,
            language: {
                emptyTable: 'Chưa có bình luận phản hồi'
            }
        });
    });
</script>
@endsection
