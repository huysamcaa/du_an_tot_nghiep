@extends('client.layouts.app')

@section('content')
<style>
    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {

        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    .checkoutPage {
    margin-top: 0 !important;
    padding-top: 0 !important;

}
.pageBannerSection {
    padding: 20px 0;
    min-height: 10px;
}

.pageBannerSection .pageBannerContent h2 {
    font-size: 38px;
    margin-bottom: 10px;
}
.pageBannerPath {
    font-size: 14px;
}
</style>

<!-- Banner -->
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Chi tiết Mã Giảm Giá</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Chi tiết Mã Giảm Giá</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chi tiết mã -->
<div class="container py-5">
    <h2 class="mt-3 mb-5 text-center fw-bold text-secondary">Chi tiết Mã Giảm Giá</h2>
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-5">

            @php
                // Lấy các giá trị từ snapshot (pivot) nếu user đã claim, ngược lại dùng bản public
                $title = $isClaimed ? ($coupon->pivot->title ?? $coupon->title) : $coupon->title;
                $code  = $isClaimed ? ($coupon->pivot->code  ?? $coupon->code ) : $coupon->code;

                $discountType  = $isClaimed ? $coupon->pivot->discount_type : $coupon->discount_type;
                $discountValue = $isClaimed ? $coupon->pivot->discount_value : $coupon->discount_value;

                // Format giá trị giảm: percent gọn (10 → "10", 10.5 → "10.5"), fixed → tiền tệ
                $discountText = $discountType === 'percent'
                    ? rtrim(rtrim(number_format($discountValue, 2, '.', ''), '0'), '.') . '%'
                    : number_format($discountValue, 0, ',', '.') . ' VND';

                // Thời gian hiển thị theo Asia/Ho_Chi_Minh
                $start = $isClaimed
                    ? ($coupon->pivot->start_date ? \Carbon\Carbon::parse($coupon->pivot->start_date)->setTimezone('Asia/Ho_Chi_Minh') : null)
                    : ($coupon->start_date          ? \Carbon\Carbon::parse($coupon->start_date)->setTimezone('Asia/Ho_Chi_Minh')          : null);

                $end = $isClaimed
                    ? ($coupon->pivot->end_date   ? \Carbon\Carbon::parse($coupon->pivot->end_date)->setTimezone('Asia/Ho_Chi_Minh')   : null)
                    : ($coupon->end_date          ? \Carbon\Carbon::parse($coupon->end_date)->setTimezone('Asia/Ho_Chi_Minh')          : null);

                $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');

                // Điều kiện (snapshot nếu đã claim)
                $maxDiscount = $isClaimed ? $coupon->pivot->max_discount_value : ($coupon->restriction->max_discount_value ?? null);
                $minOrder    = $isClaimed ? $coupon->pivot->min_order_value    : ($coupon->restriction->min_order_value ?? null);

                // Trạng thái dùng nếu đã claim
                $p = $isClaimed ? $coupon->pivot : null;
                $usedAt = ($isClaimed && $p && $p->used_at)
                    ? \Carbon\Carbon::parse($p->used_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i')
                    : null;
            @endphp

            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="text-danger fw-bold mb-1">
                        <i class="fas fa-ticket-alt me-2"></i>
                        {{ $title }}
                    </h4>

                    <span class="badge bg-dark text-white fs-5 py-2 px-3 mt-3">
                        {{ $code }}
                    </span>

                    {{-- (Tuỳ chọn) Trạng thái đã dùng/đơn liên quan khi đã claim --}}
                    @if ($isClaimed && $p)
                        @if (!is_null($p->order_id))
                            <span class="badge bg-success ms-2">
                                Đã dùng cho đơn
                                @if (Route::has('client.orders.show'))
                                    <a class="text-white text-decoration-underline ms-1" href="{{ route('client.orders.show', $p->order_id) }}">#{{ $p->order_id }}</a>
                                @else
                                    #{{ $p->order_id }}
                                @endif
                            </span>
                        @elseif ($usedAt)
                            <span class="badge bg-success ms-2">
                                Đã sử dụng lúc: {{ $usedAt }}
                            </span>
                        @endif
                    @endif
                </div>

                <div class="text-end mt-3 mt-md-0">
                    <span class="badge bg-danger text-white fs-6 px-3 py-2">
                        {{ $discountText }}
                    </span>
                </div>
            </div>

            <!-- Mô tả và thời gian -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Mô tả:</strong></p>
                    <p class="text-muted">
                        {{ $isClaimed ? ($coupon->pivot->description ?? 'Không có mô tả') : ($coupon->description ?? 'Không có mô tả') }}
                    </p>

                    @if ($isClaimed)
                        @if (!empty($coupon->pivot->user_group))
                            <p><strong>Nhóm áp dụng:</strong> {{ ucfirst($coupon->pivot->user_group) }}</p>
                        @endif
                        @if (!empty($coupon->pivot->usage_limit))
                            <p><strong>Số lần sử dụng tối đa:</strong> {{ $coupon->pivot->usage_limit }}</p>
                        @endif
                    @else
                        @if (!empty($coupon->user_group))
                            <p><strong>Nhóm áp dụng:</strong> {{ ucfirst($coupon->user_group) }}</p>
                        @endif
                        @if (!empty($coupon->usage_limit))
                            <p><strong>Số lần sử dụng tối đa:</strong> {{ $coupon->usage_limit }}</p>
                        @endif
                    @endif
                </div>

                <div class="col-md-6">
                    @if ($start)
                        <p>
                            <i class="far fa-calendar-alt me-2 text-primary"></i>
                            <strong>Hiệu lực từ:</strong>
                            {{ $start->format('d/m/Y H:i') }}
                        </p>
                    @endif

                    @if ($end)
                        <p class="mb-0">
                            <i class="far fa-clock me-2 text-danger"></i>
                            <strong>Hết hạn:</strong>
                            {{ $end->format('d/m/Y H:i') }}
                            @if ($end->between($now, $now->copy()->addHours(48)))
                                <span class="badge bg-warning text-dark ms-2">Sắp hết hạn</span>
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            @php
                $hasScope =
                    !is_null($minOrder) ||
                    ($discountType === 'percent' && !is_null($maxDiscount)) ||
                    (isset($categories) && $categories->isNotEmpty()) ||
                    (isset($products) && $products->isNotEmpty());
            @endphp

            @if ($hasScope)
                <hr class="my-4">
                <h5 class="fw-bold text-dark mb-3">📌 Điều Kiện & Phạm Vi Áp Dụng</h5>
                <div class="row ps-2">
                    <!-- Cột 1: Điều kiện -->
                    <div class="col-md-6 mb-3">
                        @if (!is_null($minOrder) && (int)$minOrder > 0)
                            <div class="mb-2">
                                <strong>Đơn hàng tối thiểu:</strong> {{ number_format($minOrder, 0, ',', '.') }} VND
                            </div>
                        @endif

                        @if ($discountType === 'percent' && !is_null($maxDiscount))
                            <div class="mb-2">
                                <strong>Giảm tối đa:</strong> {{ number_format($maxDiscount, 0, ',', '.') }} VND
                            </div>
                        @endif

                        <div class="text-muted fst-italic small mt-2">
                            * Các điều kiện này sẽ được <strong>lưu cố định</strong> khi bạn nhận mã
                        </div>
                    </div>

                    <!-- Cột 2: Danh mục & Sản phẩm -->
                    <div class="col-md-6 mb-3">
                        @if (isset($categories) && $categories->isNotEmpty())
                            <div class="mb-2">
                                <i class="fas fa-tags text-warning me-2"></i>
                                <strong>Danh mục áp dụng:</strong>
                                <div class="mt-1">
                                    @foreach ($categories as $category)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (isset($products) && $products->isNotEmpty())
                            <div class="mb-2">
                                <i class="fas fa-box text-info me-2"></i>
                                <strong>Sản phẩm áp dụng:</strong>
                                <div class="mt-1">
                                    @foreach ($products as $product)
                                        <span class="badge bg-success me-1 mb-1">{{ $product->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Back Button -->
                <a href="{{ route('client.coupons.index') }}" class="ulinaBTN">
                    <i class="fas me-1"><span>Quay lại</span></i>
                </a>
        </div>
    </div>
</div>

@endsection
