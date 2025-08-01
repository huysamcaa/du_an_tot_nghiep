@extends('client.layouts.app')

@section('content')

<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Đánh Giá Của Tôi</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a> > <span>Đánh Giá Của Tôi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Sản phẩm bạn đã đánh giá</h3>
        <a href="{{ route('client.reviews.pending') }}" class="btn btn-outline-primary">
            <i class="fa-regular fa-clock me-1"></i> Chưa đánh giá
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($reviews->count())
        @foreach ($reviews as $review)
            @if (optional($review->product)->is_active)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-wrap">

                            <div class="me-3 mb-3">
                                <a href="{{ route('product.detail', $review->product->id) }}">
                                    <img src="{{ asset('storage/' . $review->product->thumbnail) }}" width="100" height="100"
                                        class="rounded border object-fit-cover" alt="{{ $review->product->name }}">
                                </a>
                            </div>


                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between flex-wrap mb-1">
                                    <a href="{{ route('product.detail', $review->product->id) }}"
                                        class="text-decoration-none text-dark fw-bold">
                                        {{ $review->product->name }}
                                    </a>
                                    <small class="text-muted">
                                        <i class="fa-regular fa-clock me-1"></i>{{ $review->created_at->format('H:i d/m/Y') }}
                                    </small>
                                </div>


                                <div class="mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star text-warning"></i>
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                </div>


                                <p class="mb-2">{{ $review->review_text }}</p>


                                @if ($review->multimedia->count())
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @foreach ($review->multimedia as $media)
                                            @if (Str::contains($media->mime_type, 'image'))
                                                <img src="{{ asset('storage/' . $media->file) }}" width="80" height="80"
                                                    class="rounded border object-fit-cover" alt="Ảnh đánh giá"
                                                    style="cursor: pointer;" onclick="window.open(this.src)">
                                            @elseif(Str::contains($media->mime_type, 'video'))
                                                <video width="120" height="80" controls class="rounded border">
                                                    <source src="{{ asset('storage/' . $media->file) }}"
                                                        type="{{ $media->mime_type }}">
                                                </video>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif


                                <div>
                                    @if ($review->is_active === null)
                                        <span class="badge bg-secondary">Đang chờ duyệt</span>
                                    @elseif ($review->is_active === 1)
                                        <span class="badge bg-success">Đã duyệt</span>
                                    @elseif ($review->is_active === 0)
                                        <span class="badge bg-danger">Bị từ chối</span>
                                        @if ($review->reason)
                                            <div class="mt-1">
                                                <small class="text-muted fst-italic">Lý do: {{ $review->reason }}</small>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

       
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="alert alert-info">Bạn chưa có đánh giá nào.</div>
    @endif
</div>

@endsection
