@extends('client.layouts.app')

@section('content')
<div class="container">
    <h2>Chỉnh Sửa Đánh Giá</h2>

    <form action="{{ route('client.reviews.update', $review->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Sản phẩm:</label>
            <input type="text" class="form-control" value="{{ $review->product->name ?? 'Không xác định' }}" readonly>
        </div>

        <div class="form-group">
            <label>Đơn hàng:</label>
            <input type="text" class="form-control" value="{{ $review->order->id ?? 'Không xác định' }}" readonly>
        </div>

        <div class="form-group">
            <label>Xếp hạng:</label>
            <select name="rating" class="form-control" required>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} Sao</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Nội dung đánh giá:</label>
            <textarea name="review_text" class="form-control" rows="5" required>{{ old('review_text', $review->review_text) }}</textarea>
        </div>

        <div class="form-group">
            <label>File đính kèm:</label><br>
            @forelse($review->multimedia as $media)
                <a href="{{ $media->file }}" target="_blank" class="badge badge-info">{{ strtoupper($media->file_type) }}</a>
            @empty
                <span>Không có</span>
            @endforelse
            <small class="form-text text-muted">Việc thay đổi file không nằm trong chức năng này. Vui lòng liên hệ hỗ trợ.</small>
        </div>

        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        <a href="{{ route('client.reviews.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
