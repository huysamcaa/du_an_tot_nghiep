@extends('client.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Đánh Giá Của Tôi</h2>

    @forelse($reviews as $review)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $review->product->name ?? 'Sản phẩm không xác định' }}</h5>
                <h6 class="card-subtitle text-muted mb-2">Đơn hàng {{ $review->order_id }}</h6>

                <div class="mb-2">
                    <strong>Đánh giá:</strong>
                    <span class="text-warning">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                </div>

                <div class="mb-2">
                    <strong>Nội dung:</strong><br>
                    <p class="mb-1">{{ $review->review_text }}</p>
                </div>

                <div class="mb-2">
                    <strong>File đính kèm:</strong><br>
                    @forelse($review->multimedia as $media)
                        <a href="{{ $media->file }}" target="_blank" class="badge badge-info">
                            {{ strtoupper($media->file_type) }}
                        </a>
                    @empty
                        <span class="text-muted">Không có</span>
                    @endforelse
                </div>

                <div>
                    <strong>Trạng thái:</strong>
                    @if(is_null($review->is_active))
                        <span class="badge badge-warning">Chờ duyệt</span>
                    @elseif($review->is_active)
                        <span class="badge badge-success">Đã duyệt</span>
                    @else
                        <span class="badge badge-danger">Từ chối</span>
                        @if($review->reason)
                            <br><small class="text-muted">Lý do: {{ $review->reason }}</small>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            Bạn chưa có đánh giá nào.
        </div>
    @endforelse
</div>
@endsection
