@extends('client.layouts.app')

@section('content')
<div class="container">
    <h2>Đánh Giá Của Tôi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $review->product->name ?? 'Sản phẩm không xác định' }}</h5>
                <h6>Đơn hàng: {{ $review->order->id ?? 'N/A' }}</h6>

                <p>
                    <strong>Đánh giá:</strong>
                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                </p>

                <p><strong>Nội dung:</strong> {{ $review->review_text }}</p>

                <p>
                    <strong>File đính kèm:</strong>
                    @forelse($review->multimedia as $media)
                        <a href="{{ $media->file }}" target="_blank" class="badge badge-info">{{ strtoupper($media->file_type) }}</a>
                    @empty
                        <span>Không có</span>
                    @endforelse
                </p>

                <p>
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
                </p>

                <a href="{{ route('client.reviews.edit', $review->id) }}" class="btn btn-sm btn-primary">Sửa đánh giá</a>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Bạn chưa có đánh giá nào.</div>
    @endforelse
</div>
@endsection
