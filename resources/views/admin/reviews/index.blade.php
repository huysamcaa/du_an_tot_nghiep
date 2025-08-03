@extends('admin.layouts.app')


@section('content')
 

@section('title', 'Danh sách đánh giá người dùng')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Danh sách đánh giá</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="active">Đánh giá</li>
                        </ol>

                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">

                {{-- Flash messages --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách đánh giá</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Media</th>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm</th>
                                    <th>Đánh giá</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $index => $review)
                                <tr>
                                    <td>{{ $reviews->firstItem() + $index }}</td>
                                    <td>
                                        @php $firstMedia = $review->multimedia->first(); @endphp
                                        @if($firstMedia && $firstMedia->file_type === 'image')
                                        <img src="{{ asset('storage/' . $firstMedia->file) }}" width="60" alt="Ảnh">
                                        @elseif($firstMedia && $firstMedia->file_type === 'video')
                                        <video width="60" muted>
                                            <source src="{{ asset('storage/' . $firstMedia->file) }}" type="{{ $firstMedia->mime_type }}">
                                        </video>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $review->reviewer_name }}</td>
                                    <td>{{ $review->product->name ?? '—' }}</td>
                                    <td>{{ $review->rating }} ⭐</td>
                                    <td>{{ Str::limit($review->review_text, 60) }}</td>
                                    <td>
                                        @if($review->is_active === 1)
                                        <span class="badge badge-success">Đã duyệt</span>
                                        @elseif($review->is_active === 0 && $review->reason)
                                        <span class="badge badge-danger">Từ chối</span><br>
                                        <small>Lý do: {{ $review->reason }}</small>
                                        @elseif($review->is_active === 0 && !$review->reason)
                                        <span class="badge badge-warning text-dark">Chờ duyệt</span>
                                        @else
                                        <span class="badge badge-secondary">Không xác định</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_null($review->is_active))
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline-block mb-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Duyệt đánh giá">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group input-group-sm mb-1">
                                                <input type="text" name="reason" class="form-control" placeholder="Lý do từ chối" required>
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-danger" title="Từ chối đánh giá">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-muted text-center py-4">Không có đánh giá nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Hiển thị từ {{ $reviews->firstItem() ?? 0 }} đến {{ $reviews->lastItem() ?? 0 }} trên tổng số {{ $reviews->total() }} đánh giá
                            </div>
                            <div>
                                {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


 

</div>

@endsection
