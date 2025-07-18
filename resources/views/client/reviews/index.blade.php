@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
    <div class="container mt-4">
        <h2>Đánh giá của tôi</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($reviews->count())
            @foreach ($reviews as $review)
                @if (optional($review->product)->is_active)
                    <div class="border p-3 mb-3">
                        <strong>{{ $review->product->name }}</strong><br>
                        ⭐ {{ $review->rating }} / 5<br>
                        <p>{{ $review->review_text }}</p>

                        @if ($review->multimedia->count())
                            <div class="d-flex gap-2">
                                @foreach ($review->multimedia as $media)
                                    @if (Str::contains($media->mime_type, 'image'))
                                        <img src="{{ asset('storage/' . $media->file) }}" width="100">
                                    @elseif(Str::contains($media->mime_type, 'video'))
                                        <video width="180" controls>
                                            <source src="{{ asset('storage/' . $media->file) }}"
                                                type="{{ $media->mime_type }}">
                                        </video>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach

            {{ $reviews->links() }}
        @else
            <p>Bạn chưa có đánh giá nào.</p>
        @endif
    </div>
 </div>
@endsection
