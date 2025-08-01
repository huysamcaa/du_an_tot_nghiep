@extends('admin.layouts.app')

@section('content')

<h1 class="mb-4">Danh Sách Đánh Giá Người Dùng</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Thanh Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item d-flex justify-content-between w-100">
            <span>Admin</span>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Trang chủ</a>
                <a href="{{ route('admin.reviews.index') }}" class="breadcrumb-item">Đánh giá</a>
                <span class="breadcrumb-item active">Danh Sách đánh giá</span>
            </div>
        </li>
    </ol>
</nav>


<!-- Bảng Danh Sách Đánh Giá -->
<table class="table table-bordered table-striped">
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

                    </form><br>


                    {{-- <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3" style="max-width:350px;">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm" value="{{ request('keyword') }}">
                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                        </div>
                    </form> --}}
    <thead class="">
        <tr>
            <th>STT</th>
            <th>Người Dùng</th>
            <th>Sản Phẩm</th>
            <th>Đơn Hàng</th>
            <th>Đánh Giá</th>
            <th>Nội Dung</th>
            <th>File Đính Kèm</th>
            <th>Trạng Thái</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reviews as $index => $review)
            <tr>
                <td>{{ $loop->iteration }}</td>
               <td>{{ $review->reviewer_name }}</td>
                <td>{{ $review->product->name ?? '---' }}</td>
                <td>{{ $review->order_id }}</td>
                <td>{{ $review->rating }} ⭐</td>
                <td>{{ $review->review_text }}</td>
   <td>
    <div class="d-flex flex-wrap gap-2" style="max-width: 250px;">
        @forelse($review->multimedia as $media)
            @if($media->file_type === 'image')
                <a href="{{ asset('storage/' . $media->file) }}" target="_blank"
                   style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid #ccc; display: block;">
                    <img src="{{ asset('storage/' . $media->file) }}"
                         alt="Ảnh"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </a>
            @elseif($media->file_type === 'video')
                <a href="{{ asset('storage/' . $media->file) }}" target="_blank"
                   style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 1px solid #ccc; display: block;">
                    <video style="width: 100%; height: 100%; object-fit: cover;" muted>
                        <source src="{{ asset('storage/' . $media->file) }}" type="{{ $media->mime_type }}">
                    </video>
                </a>
            @endif
        @empty
            <span class="text-muted">--</span>
        @endforelse
    </div>
</td>

                <td>
    @if($review->is_active === 1)
        <span class="badge badge-success">Đã duyệt</span>
    @elseif($review->is_active === 0 && $review->reason)
        <span class="badge badge-danger">Từ chối</span><br>
        <small>Lý do: {{ $review->reason }}</small>
    @elseif($review->is_active === 0 && !$review->reason)
        <span class="badge badge-warning">Chờ duyệt</span>
    @else
        <span class="badge badge-secondary">Không xác định</span>
    @endif
</td>

                <td>
                    @if(is_null($review->is_active))
                        <!-- Form DUYỆT -->
<form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline-block mb-1">
    @csrf
    @method('PATCH') <!-- Bắt buộc để Laravel hiểu đây là PATCH -->
    <button type="submit" class="btn btn-sm btn-success">Duyệt</button>
</form>

<!-- Form TỪ CHỐI -->
<form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline-block">
    @csrf
    @method('PATCH')
    <input type="text" name="reason" class="form-control form-control-sm mb-1" placeholder="Lý do từ chối" required>
    <button type="submit" class="btn btn-sm btn-danger">Từ chối</button>
</form>

                    @else
                        <span class="text-muted">--</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">Không có đánh giá nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Phân trang -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
        Hiển thị từ {{ $reviews->firstItem() ?? 0 }} đến {{ $reviews->lastItem() ?? 0 }} trong tổng số {{ $reviews->total() }} đánh giá
    </div>

    <div>
        @if ($reviews->hasPages())
            {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
        @else
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item active"><span class="page-link">1</span></li>
                </ul>
            </nav>
        @endif
    </div>
</div>


@endsection
