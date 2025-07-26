@extends('client.layouts.app')

@section('content')

    
        <section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Đánh Giá Của Tôi</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>  >  <span>Đánh Giá Của Tôi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container mt-4">

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
