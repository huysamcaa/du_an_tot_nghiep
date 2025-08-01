@extends('client.layouts.app')

@section('content')
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Đánh Giá Sản Phẩm</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a> > <span>Đánh Giá</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <h3 class="text-center fw-bold display-6 my-4">Đánh giá sản phẩm</h3>

    <div class="container mt-4">
        <div class="text-end">
            <a href="{{ route('client.orders.purchase.history') }}"
                class="btn btn-warning px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-arrow-left-circle me-2"></i>Quay lại lịch sử đơn hàng
            </a>
        </div>
    </div>

    <div class="container py-4">
        @if (session('review_submitted'))
            <div class="alert alert-success text-center">
                🎉 Cảm ơn bạn đã đánh giá! Đánh giá của bạn đang chờ duyệt.
            </div>
            <div class="text-center mb-4">
    <a href="{{ route('client.reviews.index') }}"
       class="btn btn-info px-4 py-2 rounded-pill shadow-sm review-btn">
        <i class="bi bi-arrow-left-circle me-2 review-icon"></i>
        Xem sản phẩm đã đánh giá
    </a>
</div>

        @endif

        @if ($pendingReviews->isEmpty() && !session('review_submitted'))
            <div class="alert alert-info text-center">
                Không có sản phẩm nào chờ đánh giá.
            </div>
             <div class="text-center mb-4">
    <a href="{{ route('client.reviews.index') }}"
       class="btn btn-info px-4 py-2 rounded-pill shadow-sm review-btn">
        <i class="bi bi-arrow-left-circle me-2 review-icon"></i>
        Xem sản phẩm đã đánh giá
    </a>
</div>

        @endif

        @foreach ($pendingReviews as $index => $item)
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/' . $item['product']->thumbnail) }}" alt="ảnh" width="60"
                            height="60" class="rounded border me-3 object-fit-cover">
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $item['product']->name }}</h6>
                        </div>
                    </div>

                    {{-- Toggle mở form bằng HTML thuần --}}
                    <details class="mt-2">
                        <summary class="btn btn-outline-success px-3 py-2 rounded-pill">
                            Đánh giá ngay
                        </summary>

                        <form action="{{ route('client.reviews.store') }}" method="POST" enctype="multipart/form-data"
                            class="border-top pt-3 mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                            <input type="hidden" name="order_id" value="{{ $item['order_id'] }}">

                            {{-- Số sao --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số sao:</label>
                                <select name="rating"
                                    class="form-select w-auto d-inline-block @error('rating') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn sao --</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} sao</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nội dung --}}
                            <div class="mb-3">
                                <textarea name="review_text" class="form-control @error('review_text') is-invalid @enderror" rows="3"
                                    placeholder="Chia sẻ cảm nhận của bạn..." required>{{ old('review_text') }}</textarea>
                                @error('review_text')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Hình ảnh / video --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hình ảnh / video (tùy chọn):</label>
                                <input type="file" name="media[]"
                                    class="form-control @error('media.*') is-invalid @enderror" multiple
                                    accept="image/*,video/mp4">
                                <small class="text-muted fst-italic">Tối đa 4 ảnh và 1 video.</small>
                                @error('media.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-info px-4 py-2 rounded-pill shadow-sm">
                                    Gửi đánh giá
                                </button>
                            </div>

                        </form>
                    </details>
                </div>
            </div>
        @endforeach

    </div>
@endsection
