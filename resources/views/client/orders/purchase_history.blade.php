@extends('client.layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('content')
    <!-- Banner Section -->
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2 class="banner-title">Đơn Hàng Của Tôi</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">
                                <i class="fas fa-home me-1"></i>Trang chủ
                            </a>
                            <span class="separator">/</span>
                            <span class="current">Đơn Hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="orderHistorySection py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                                        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    @if ($orders->isEmpty())

                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h4 class="empty-state-title">Chưa có đơn hàng nào</h4>
                            <p class="empty-state-text">Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên của bạn!</p>
                            <a href="{{ route('client.products.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i>Khám phá sản phẩm
                            </a>
                        </div>
                    @else
                        <!-- Filter Tabs -->
                        <div class="order-filters mb-4">
                            <ul class="nav nav-pills justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="pill" href="#all-orders">
                                        <i class="fas fa-list me-2"></i>Tất cả
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#pending-orders">
                                        <i class="fas fa-clock me-2"></i>Chờ xác nhận
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#processing-orders">
                                        <i class="fas fa-box me-2"></i>Đang xử lý
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#completed-orders">
                                        <i class="fas fa-check-circle me-2"></i>Hoàn thành
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Orders List -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="all-orders">
                                @foreach ($orders as $order)
                                    <div class="order-card">
                                        <!-- Order Header -->
                                        <div class="order-header">
                                            <div class="order-info">
                                                <div class="order-code">
                                                    <i class="fas fa-receipt me-2"></i>
                                                    <span class="fw-bold">#{{ $order->code }}</span>
                                                </div>
                                                <div class="order-date">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>

                                            <div class="order-status-badges">
                                                <!-- Payment Status -->
                                                @if ($order->is_paid)
                                                    <span class="status-badge paid">
                                                        <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                                    </span>
                                                @else
                                                    <span class="status-badge unpaid">
                                                        <i class="fas fa-clock me-1"></i>
                                                    </span>
                                                @endif

                                                <!-- Order Status -->
                                                @if ($order->currentStatus)
                                                    <span class="status-badge order-status">
                                                        {{ $order->currentStatus->orderStatus->name }}
                                                    </span>
                                                @else
                                                    <span class="status-badge no-status">Chưa có trạng thái</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Order Items -->
                                        <div class="order-body">
                                            @foreach ($order->items as $item)
                                                <div class="order-item">
                                                    <div class="item-image">
                                                        @if ($item->product && $item->product->thumbnail)
                                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                                alt="{{ $item->product->name }}" class="product-thumbnail">
                                                        @else
                                                            <div class="product-placeholder">
                                                                <i class="fas fa-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="item-details">
                                                        <h6 class="product-name">
                                                            {{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</h6>
                                                        <p class="product-variant">
                                                            <i class="fas fa-tag me-1"></i>
                                                            {{ $item->variant->name ?? 'Không phân loại' }}
                                                        </p>
                                                        <div class="quantity-badge">
                                                            <i class="fas fa-times me-1"></i>{{ $item->quantity }}
                                                        </div>
                                                    </div>

                                                    <div class="item-price">
                                                        <span
                                                            class="price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</span>
                                                    </div>
                                                    

                                                    <div class="item-actions">
                                                                                                    @php
                                                        $pending = $order->refunds->firstWhere('status', 'pending');
                                                    @endphp

                                                    @if ($pending)
                                                        {{-- Form hủy yêu cầu hoàn đơn --}}
                                                        <form id="refund-cancel-{{ $pending->id }}"
                                                            action="{{ route('refunds.cancel', ['id' => $pending->id]) }}"
                                                            method="POST"
                                                            style="display:none">
                                                            @csrf
                                                        </form>

                                                        <button type="submit"
                                                            form="refund-cancel-{{ $pending->id }}"
                                                            class="btn btn-outline-warning btn-sm action-btn"
                                                            onclick="return confirm('Bạn có chắc chắn muốn hủy yêu cầu này?');">
                                                            <i class="fas fa-times me-1"></i>Hủy hoàn
                                                        </button>
                                                    @elseif (
                                                        $order->currentStatus?->orderStatus?->name === 'Đã hoàn thành' &&
                                                        $order->refunds->whereIn('status', ['pending', 'receiving', 'completed'])->count() === 0
                                                    )
                                                        {{-- Nút tạo yêu cầu hoàn đơn (chỉ khi chưa có refund đang xử lý) --}}
                                                        <a href="{{ route('refunds.select_items', ['order_id' => $order->id]) }}"
                                                        class="btn btn-outline-warning btn-sm action-btn">
                                                            <i class="fas fa-undo-alt me-1"></i>Hoàn đơn
                                                        </a>
                                                    @endif
                                                        <button class="btn btn-outline-primary btn-sm action-btn">
                                                            <i class="fas fa-redo-alt me-1"></i>Mua lại
                                                        </button>
                                                        <button class="btn btn-outline-success btn-sm action-btn">
                                                            <i class="fas fa-comments me-1"></i>Chat
                                                        </button>
                                                        @php
                                                            $key = $order->id . '-' . ($item->product->id ?? 'null');
                                                            $alreadyReviewed = $reviewedMap[$key] ?? false;
                                                        @endphp
                                                        @if ($order->currentStatus?->orderStatus?->name === 'Đã hoàn thành' && $item->product && !$alreadyReviewed)
                                                            <button
                                                                class="btn btn-outline-warning btn-sm action-btn open-review-form"
                                                                data-order-id="{{ $order->id }}"
                                                                data-product-id="{{ $item->product->id }}">
                                                                <i class="fas fa-star me-1"></i>Đánh giá
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('client.reviews.index') }}"
                                                            class="btn btn-outline-warning btn-sm action-btn review-link">
                                                            <i class="fas fa-star me-1 review-icon"></i>Xem đánh giá
                                                        </a>
                                                    </div>
                                                </div>
                                                  @if ($order->currentStatus?->orderStatus?->name === 'Đã hoàn thành' && $item->product && !$alreadyReviewed)
                                                    <div class="review-form-wrapper d-none mt-3" id="review-form-{{ $order->id }}-{{ $item->product->id }}">
                                                        <form action="{{ route('client.reviews.store') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">

                                                            <div class="mb-2">
                                                                <label>Đánh giá sao:</label>
                                                                <select name="rating" class="form-select" required>
                                                                    <option value="">-- Chọn sao --</option>
                                                                    @for ($i = 5; $i >= 1; $i--)
                                                                        <option value="{{ $i }}">{{ $i }} sao</option>
                                                                    @endfor
                                                                </select>
                                                            </div>

                <div class="mb-2">
                    <label>Nội dung:</label>
                    <textarea name="review_text" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-2">
                    <label>Hình ảnh / video:</label>
                    <input type="file" name="media[]" class="form-control" multiple>
                </div>

                <button type="submit" class="btn btn-success btn-sm submit-review-btn">
                    <i class="fas fa-paper-plane me-1"></i>Gửi đánh giá
                </button>
            </form>
        </div>
    @endif
                                                @if (!$loop->last)
                                                    <hr class="item-separator">
                                                @endif
                                            @endforeach
                                        </div>




                                        <!-- Order Footer -->
                                        <div class="order-footer">
                                            <div class="shop-info">
                                                <i class="fas fa-store me-2"></i>
                                                <span class="shop-name">{{ $order->shop->name ?? ' FreshFit.vn' }}</span>
                                            </div>

                                            <div class="order-actions">
                                                <a href="{{ route('client.orders.show', $order->code) }}"
                                                    class="btn btn-outline-info btn-sm me-2">
                                                    <i class="fas fa-eye me-1"></i>Chi tiết
                                                </a>

                                                <div class="total-amount">
                                                    <span class="total-label">Tổng tiền:</span>
                                                    <span
                                                        class="total-price">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if ($orders->hasPages())
                            <div class="pagination-wrapper">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Banner Section */
        .banner-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pageBannerPath {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .pageBannerPath a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pageBannerPath a:hover {
            color: #bbf7d0;
            text-shadow: 0 0 10px rgba(187, 247, 208, 0.5);
        }

        .separator {
            margin: 0 10px;
            opacity: 0.7;
        }

        .current {
            color: #bbf7d0;
            font-weight: 600;
        }

        /* Main Section */
        .orderHistorySection {
            background: linear-gradient(to bottom, #ecf5f4, #ffffff);
            min-height: 100vh;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .empty-state-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .empty-state-text {
            color: #6c757d;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Filter Tabs */
        .order-filters {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .nav-pills .nav-link {
            border-radius: 25px;
            padding: 12px 24px;
            font-weight: 500;
            color: #6c757d;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-pills .nav-link:hover {
            color: #16a34a;
            background-color: #ecf5f4;
            transform: translateY(-2px);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(74, 222, 128, 0.4);
            transform: translateY(-2px);
        }

        /* Order Cards */
        .order-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        /* Order Header */
        .order-header {
            background: linear-gradient(135deg, #ecf5f4 0%, #d1fae5 100%);
            padding: 20px 25px;
            border-bottom: 1px solid rgba(34, 197, 94, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-info {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .order-code {
            font-size: 1.1rem;
            color: #495057;
        }

        .order-date {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .order-status-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Status Badges */
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.paid {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            box-shadow: 0 3px 10px rgba(34, 197, 94, 0.3);
        }

        .status-badge.unpaid {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
        }

        .status-badge.order-status {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
        }

        .status-badge.no-status {
            background: #6c757d;
            color: white;
        }

        /* Order Body */
        .order-body {
            padding: 25px;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px 0;
        }

        .item-image {
            flex-shrink: 0;
        }

        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .product-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ecf5f4, #d1fae5);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            font-size: 1.5rem;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-variant {
            color: #6c757d;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .quantity-badge {
            display: inline-flex;
            align-items: center;
            background: #ecf5f4;
            color: #16a34a;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .item-price {
            text-align: right;
            margin-right: 20px;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #16a34a;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-shrink: 0;
        }

        .action-btn {
            min-width: 120px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .item-separator {
            margin: 20px 0;
            border-color: rgba(0, 0, 0, 0.08);
        }

        /* Order Footer */
        .order-footer {
            background: #f7fef9;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .shop-info {
            color: #495057;
            font-weight: 500;
        }

        .shop-name {
            color: #16a34a;
            font-weight: 600;
        }

        .order-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .total-amount {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .total-label {
            color: #6c757d;
            font-weight: 500;
        }

        .total-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #16a34a;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }
.review-form-wrapper {
    max-height: 0;
    overflow: hidden;
    transition: all 0.4s ease;
    opacity: 0;
}

.review-form-wrapper.active {
    max-height: 500px;
    opacity: 1;
    margin-top: 1rem;
}

        /* Responsive Design */
        @media (max-width: 768px) {
            .banner-title {
                font-size: 2rem;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .item-actions {
                flex-direction: row;
                justify-content: center;
            }

            .order-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-pills .nav-link {
                margin: 5px 2px;
                padding: 10px 16px;
                font-size: 0.9rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .order-card:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .order-card:nth-child(even) {
            animation-delay: 0.2s;
        }
    </style>

    <script>
        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Add loading animation to buttons
           document.querySelectorAll('.action-btn:not([type="submit"])').forEach(button => {
                button.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                });
            });
            // Mở form đánh giá khi nhấn nút "Đánh giá"
document.querySelectorAll('.open-review-form').forEach(button => {
    button.addEventListener('click', function () {
        const orderId = this.dataset.orderId;
        const productId = this.dataset.productId;
        const form = document.getElementById(`review-form-${orderId}-${productId}`);
        if (form) {
            form.classList.remove('d-none');
            form.classList.add('active');
            this.classList.add('d-none');

            // Scroll đến form
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Focus vào textarea đầu tiên
            const textarea = form.querySelector('textarea');
            if (textarea) textarea.focus();
        }
    });
});

    });


    </script>
@endsection

