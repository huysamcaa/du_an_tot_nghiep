@extends('client.layouts.app')

@section('content')
    
        <!-- Banner -->
        <section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Chi tiết Mã Giảm Giá</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Chi tiết Mã
                                    Giảm Giá</span>
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

                    <!-- Header -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="text-info fw-bold mb-1">
                                <i class="fas fa-ticket-alt me-2"></i>{{ $coupon->title }}
                            </h4>
                            <span class="badge bg-dark text-white fs-5 py-2 px-3">
                                {{ $coupon->code }}
                            </span>
                        </div>
                        <div class="text-end mt-3 mt-md-0">
                            <span class="badge bg-info text-white fs-6 px-3 py-2">
                                {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : number_format($coupon->discount_value, 0, ',', '.') . ' VND' }}
                            </span>
                        </div>
                    </div>

                    <!-- Mô tả và thời gian -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Mô tả:</strong></p>
                            <p class="text-muted">{{ $coupon->description ?? 'Không có mô tả' }}</p>

                            @if ($coupon->user_group)
                                <p><strong>Nhóm áp dụng:</strong> {{ ucfirst($coupon->user_group) }}</p>
                            @endif
                            @if ($coupon->usage_limit)
                                <p><strong>Số lần sử dụng tối đa:</strong> {{ $coupon->usage_limit }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if ($coupon->start_date)
                                <p>
                                    <i class="far fa-calendar-alt me-2 text-primary"></i>
                                    <strong>Hiệu lực từ:</strong> {{ $coupon->start_date->format('d/m/Y H:i') }}
                                </p>
                            @endif
                            @if ($coupon->end_date)
                                <p>
                                    <i class="far fa-clock me-2 text-danger"></i>
                                    <strong>Hết hạn:</strong> {{ $coupon->end_date->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Điều kiện -->
                    @if (
                        $coupon->restriction ||
                            (isset($categories) && $categories->isNotEmpty()) ||
                            (isset($products) && $products->isNotEmpty()))

                        <hr class="my-4">
                        <h5 class="fw-bold text-dark mb-3">📌 Điều Kiện & Phạm Vi Áp Dụng</h5>
                        <div class="row ps-2">
                            <!-- Cột 1: Điều kiện -->
                            <div class="col-md-6 mb-3">
                                @if ($coupon->restriction)
                                    <div class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Đơn hàng tối thiểu:</strong>
                                        {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} VND
                                    </div>
                                    <div class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Giảm tối đa:</strong>
                                        {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }} VND
                                    </div>
                                @endif
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
                    <div class="mt-4">
                        <a href="{{ route('client.coupons.index') }}" class="btn btn-outline-info">
                            ← Quay lại danh sách mã
                        </a>
                    </div>

                </div>
            </div>
        </div>

    @endsection
