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

{{-- <!-- Tạo phần "Show entries" và tìm kiếm -->
<form method="GET" action="{{ route('admin.reviews.index') }}" class="d-flex justify-content-between mb-3">
    <!-- Phân trang -->
    <div class="d-flex align-items-center">
        <label for="entries" class="mr-2">Show</label>
        <select name="perPage" class="form-control d-inline w-auto" onchange="this.form.submit()">
            <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
        </select>
        entries
    </div>

    <!-- Tìm kiếm -->
    <div class="d-flex align-items-center">
        <label for="search" class="mr-2">Search:</label>
        <input type="text" name="search" class="form-control d-inline w-auto" value="{{ request('search') }}" placeholder="Tìm kiếm ">
        <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
    </div>
</form> --}}

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
                        
                    </form>
               
            
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3" style="max-width:350px;">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm tên danh mục..." value="{{ request('keyword') }}">
                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                        </div>
                    </form>
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
                    @forelse($review->multimedia as $media)
                        <a href="{{ $media->file }}" target="_blank" class="badge badge-info">{{ strtoupper($media->file_type) }}</a><br>
                    @empty
                        <span class="text-muted">--</span>
                    @endforelse
                </td>
                <td>
                    @if(is_null($review->is_active))
                        <span class="badge badge-warning">Chờ duyệt</span>
                    @elseif($review->is_active)
                        <span class="badge badge-success">Đã duyệt</span>
                    @else
                        <span class="badge badge-danger">Từ chối</span><br>
                        <small>Lý do: {{ $review->reason }}</small>
                    @endif
                </td>
                <td>
                    @if(is_null($review->is_active))
                        <!-- Form DUYỆT -->
<form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline-block mb-1">
    @csrf
    @method('PATCH') <!-- ✅ Bắt buộc để Laravel hiểu đây là PATCH -->
    <button type="submit" class="btn btn-sm btn-success">Duyệt</button>
</form>

<!-- Form TỪ CHỐI -->
<form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline-block">
    @csrf
    @method('PATCH') <!-- ✅ Thêm vào đây nữa -->
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
<div class="d-flex justify-content-center">
    {{ $reviews->links('pagination::simple-bootstrap-4') }}
</div>

@endsection
