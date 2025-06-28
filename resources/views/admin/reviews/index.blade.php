@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">Quản Lý Đánh Giá Người Dùng</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-warning"> {{-- 👈 Sửa tại đây --}}
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
                <td>{{ $review->user->fullname ?? 'Ẩn danh' }}</td>
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
                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline-block mb-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Duyệt</button>
                        </form>
                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline-block">
                            @csrf
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
@endsection
