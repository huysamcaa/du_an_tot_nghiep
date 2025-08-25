@extends('client.layouts.app')
@section('title','Danh Sách Mã Khuyến mãi Đã Nhận')
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


.coupon-card {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff5f5; /* nền hồng nhạt */
    border: 1px solid #ffcccc;
    border-radius: 8px;
    padding: 16px 20px;
    font-family: 'Jost', sans-serif;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: transform 0.2s ease-in-out;
    height: 120px;
}
.coupon-card:hover {
    transform: translateY(-3px);
}

.coupon-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.coupon-left h5 {
    font-size: 20px;
    font-weight: 700;
    color: #e53935;
    margin: 0 0 6px;
}
.coupon-left p {
    font-size: 14px;
    margin: 2px 0;
    color: #555;
}
.coupon-left .expiry {
    font-size: 12px;
    color: #777;
    margin-top: 6px;
}

.coupon-right {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-left: 1px dashed #ff9999;
    padding-left: 20px;
    min-width: 90px;
}

.coupon-right a {
    background: #e53935;
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.coupon-right a:hover {
    background: #d32f2f;
}

</style>


    <!-- Banner -->
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Mã Giảm Giá Đã Nhận</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Mã Giảm Giá Đã
                                Nhận</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Content -->
    <div class="container py-5">
        <h2 class="text-center mb-4 text-dark">Danh sách mã giảm giá đã nhận</h2>

        <!-- Bộ lọc -->
        <div class="text-center mb-4">
            <div>
                <a href="{{ route('client.coupons.received') }}"
                class="ulinaBTN {{ !request('status') ? 'active' : '' }}">
                <span>Tất cả</span>
                </a>
            <a href="{{ route('client.coupons.received', ['status' => 'unused']) }}"
                class="ulinaBTN {{ request('status') === 'unused' ? 'active' : '' }}">
                <span>Chưa sử dụng</span>
                </a>
            <a href="{{ route('client.coupons.received', ['status' => 'used']) }}"
                class="ulinaBTN {{ request('status') === 'used' ? 'active' : '' }}">
                <span>Đã sử dụng</span>
                </a>
            </div>
                <a href="{{ route('client.coupons.index') }}" class="btn btn-outline-secondary rounded-5">
                    <span><i class="fas fa-arrow-left me-1"></i>Quay lại</span>
                </a>
        </div>

        @auth
            <div class="text-center mb-4">

            </div>
        @endauth

        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning text-center">{{ session('warning') }}</div>
        @endif

        @if ($coupons->isEmpty())
            <div class="alert alert-info text-center">Hiện không có mã giảm giá nào đang hoạt động.</div>
        @else
            <div class="row g-4">
                @foreach ($coupons as $coupon)
                    {{-- <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border border-info rounded-3 shadow-sm">
                            <div class="card-body d-flex flex-column p-4">
                                <!-- Header -->
                                <div class="text-center mb-3">
                                    <h5 class="text-uppercase fw-bold text-info">
                                        {{ $coupon->pivot->code }}
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        {{ $coupon->pivot->title }}
                                    </p>
                                    <span class="badge bg-info text-white fs-6 px-3 py-2">
                                        {{ $coupon->pivot->discount_type === 'percent'
                                            ? rtrim(rtrim(number_format($coupon->pivot->discount_value, 2, '.', ''), '0'), '.') . '%'
                                            : number_format($coupon->pivot->discount_value, 0, ',', '.') . ' VNĐ' }}
                                    </span>

                                </div>

                                <!-- Info -->
                                <div class="mb-2 small text-muted">
                                    <strong>Nhóm áp dụng:</strong>
                                    {{ $coupon->pivot->user_group ? ucfirst($coupon->pivot->user_group) : 'Tất cả người dùng' }}
                                </div>


                                <ul class="list-unstyled small text-muted mb-2">
                                    @if ($coupon->pivot->start_date)
                                        <li><i class="far fa-calendar me-1"></i> Bắt đầu:
                                            {{ \Carbon\Carbon::parse($coupon->pivot->start_date)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                                        </li>
                                    @endif

                                    @if ($coupon->pivot->end_date)
                                        <li><i class="far fa-clock me-1"></i> Kết thúc:
                                            {{ \Carbon\Carbon::parse($coupon->pivot->end_date)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                                            @php
                                                $end = \Carbon\Carbon::parse($coupon->pivot->end_date)->setTimezone(
                                                    'Asia/Ho_Chi_Minh',
                                                );
                                                $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
                                            @endphp
                                            @if ($end->between($now, $now->copy()->addHours(48)))
                                                <span class="badge bg-warning text-dark ms-2">Sắp hết hạn</span>
                                            @endif
                                        </li>
                                    @endif



                                </ul>

                                <div class="small text-muted mb-3">
                                    <div>💰 Tối thiểu:
                                        {{ number_format($coupon->pivot->min_order_value ?? 0, 0, ',', '.') }} VNĐ
                                    </div>
                                    @if (!is_null($coupon->pivot->max_discount_value))
                                        <div>🔻 Tối đa:
                                            {{ number_format($coupon->pivot->max_discount_value, 0, ',', '.') }} VNĐ
                                        </div>
                                    @endif
                                </div>
                                @if (!empty($coupon->pivot->category_names))
                                    <div class="text-muted small mt-2">
                                        <strong>Danh mục áp dụng:</strong>
                                        @foreach ($coupon->pivot->category_names as $categoryName)
                                            <span class="badge bg-secondary">{{ $categoryName }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                @if (!empty($coupon->pivot->product_names))
                                    <div class="text-muted small mt-2">
                                        <strong>Sản phẩm áp dụng:</strong>
                                        @foreach ($coupon->pivot->product_names as $productName)
                                            <span class="badge bg-success">{{ $productName }}</span>
                                        @endforeach
                                    </div>
                                @endif





                                <!-- Footer -->

                                @php
                                    $p = $coupon->pivot;
                                    $isLocked = !is_null($p->order_id);
                                    $isUsed = $isLocked || !is_null($p->used_at);
                                    $usedAt = $p->used_at
                                        ? \Carbon\Carbon::parse($p->used_at)
                                            ->setTimezone('Asia/Ho_Chi_Minh')
                                            ->format('d/m/Y H:i')
                                        : null;
                                @endphp

                                <div class="mt-auto">
                                    <a href="{{ route('client.coupons.show', $coupon->id) }}"
                                        class="btn btn-primary btn-sm w-100 mb-2">
                                        Xem chi tiết
                                    </a>

                                    <div class="mt-3 text-center">
                                        @if ($isUsed)
                                            @if ($isLocked)
                                                <span
                                                    class="badge bg-success px-3 py-2 fs-6 d-inline-flex align-items-center gap-1">
                                                    <i class="fas fa-check-circle"></i>
                                                    Đã dùng cho đơn
                                                    @if (Route::has('client.orders.show'))
                                                        <a class="text-white text-decoration-underline ms-1"
                                                            href="{{ route('client.orders.show', $p->order_id) }}">#{{ $p->order_id }}</a>
                                                    @else
                                                        #{{ $p->order_id }}
                                                    @endif
                                                </span>
                                            @elseif ($usedAt)
                                                <span class="badge bg-success px-3 py-2 fs-6">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Đã sử dụng lúc: {{ $usedAt }}
                                                </span>
                                            @endif


                                            @if (!is_null($p->discount_applied))
                                                <div class="small text-muted mt-2">
                                                    Giảm thực tế:
                                                    <strong>{{ number_format((int) $p->discount_applied, 0, ',', '.') }}
                                                        VNĐ</strong>
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 fs-6">
                                                <i class="fas fa-clock me-1"></i>
                                                Chưa sử dụng
                                            </span>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div> --}}
                     @php
                        $p = $coupon->pivot;
                        $isLocked = !is_null($p->order_id);
                        $isUsed = $isLocked || !is_null($p->used_at);
                        $usedAt = $p->used_at
                            ? \Carbon\Carbon::parse($p->used_at)
                                ->setTimezone('Asia/Ho_Chi_Minh')
                                ->format('d/m/Y H:i')
                            : null;
                    @endphp
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="coupon-card">
                        <div class="coupon-left">
                            <h5>
                                @if ($coupon->pivot->discount_type === 'percent')
                                {{ $coupon->pivot->code }} -
                                    Giảm {{ rtrim(rtrim(number_format($coupon->pivot->discount_value, 2, '.', ''), '0'), '.') }}%
                                @else
                                {{ $coupon->pivot->code }} -
                                    Giảm {{ number_format($coupon->pivot->discount_value, 0, ',', '.') }} VNĐ
                                @endif
                            </h5>
                            <p>Đơn tối thiểu: {{ number_format($coupon->pivot->min_order_value ?? 0, 0, ',', '.') }} VNĐ</p>
                            @if ($coupon->pivot->end_date)
                                <p class="expiry">
                                    HSD: {{ \Carbon\Carbon::parse($coupon->pivot->end_date)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                        <div class="coupon-right">
                            <a href="{{ route('client.coupons.show', $coupon->id) }}" class="btn btn-danger">
                                        Xem chi tiết
                                    </a>
                                    <div class="mt-3 text-center">
                                        @if ($isUsed)
                                            @if ($isLocked)
                                                <span
                                                    >
                                                    <i class="fas fa-check-circle"></i>
                                                    Đã dùng cho đơn
                                                    @if (Route::has('client.orders.show'))
                                                        <a href="{{ route('client.orders.show', $p->order_id) }}" class="badge bg-secondary">#{{ $p->order_id }}</a>
                                                    @else
                                                        #{{ $p->order_id }}
                                                    @endif
                                                </span>
                                            @elseif ($usedAt)
                                                <span >
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Đã sử dụng lúc: {{ $usedAt }}
                                                </span>
                                            @endif


                                            @if (!is_null($p->discount_applied))
                                                <div class="small text-muted mt-2">
                                                    Giảm thực tế:
                                                    <strong>{{ number_format((int) $p->discount_applied, 0, ',', '.') }}
                                                        VNĐ</strong>
                                                </div>
                                            @endif
                                        @else
                                            <span >
                                                <i class="fas fa-clock me-1"></i>
                                                Chưa sử dụng
                                            </span>
                                        @endif
                                    </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
