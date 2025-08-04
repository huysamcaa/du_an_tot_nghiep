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
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
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
                                    <button class="nav-link active filter-btn" data-filter="all">
                                        <i class="fas fa-list me-2"></i>Tất cả
                                        <span class="count-badge" id="count-all">{{ $orders->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link filter-btn" data-filter="pending">
                                        <i class="fas fa-clock me-2"></i>Chờ xác nhận
                                        <span class="count-badge" id="count-pending">0</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link filter-btn" data-filter="processing">
                                        <i class="fas fa-box me-2"></i>Đang xử lý
                                        <span class="count-badge" id="count-processing">0</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link filter-btn" data-filter="completed">
                                        <i class="fas fa-check-circle me-2"></i>Hoàn thành
                                        <span class="count-badge" id="count-completed">0</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link filter-btn" data-filter="cancelled">
                                        <i class="fas fa-times-circle me-2"></i>Đã hủy
                                        <span class="count-badge" id="count-cancelled">0</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Search Box -->
                        <div class="search-box mb-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="orderSearch"
                                    placeholder="Tìm kiếm theo mã đơn hàng hoặc tên sản phẩm...">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Orders List -->
                        <div class="orders-container">
                            @foreach ($orders as $order)
                                @php
                                    $statusName = $order->currentStatus->orderStatus->name ?? 'Chưa có trạng thái';
                                    $statusClass = '';

                                    // Phân loại trạng thái
                                    switch ($statusName) {
                                        case 'Chờ xử lý':
                                        case 'Chờ xác nhận':
                                        case 'Đang chờ xác nhận':
                                            $statusClass = 'pending';
                                            break;
                                        case 'Đang xử lý':
                                        case 'Đang chuẩn bị':
                                        case 'Đang giao hàng':
                                        case 'Đã xác nhận':
                                        case 'Đã gửi hàng':
                                            $statusClass = 'processing';
                                            break;
                                        case 'Đã hoàn thành':
                                        case 'Hoàn thành':
                                        case 'Đã giao hàng':
                                            $statusClass = 'completed';
                                            break;
                                        case 'Đã hủy':
                                        case 'Hủy đơn hàng':
                                            $statusClass = 'cancelled';
                                            break;
                                        default:
                                            $statusClass = 'pending';
                                    }
                                @endphp

                                <div class="order-card" data-status="{{ $statusClass }}"
                                    data-order-code="{{ $order->code }}" data-order-id="{{ $order->id }}">
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
                                            <span class="status-badge order-status status-{{ $statusClass }}">
                                                {{ $statusName }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <div class="order-body">
                                        @foreach ($order->items as $item)
                                            @php
                                                $key = $order->id . '-' . ($item->product->id ?? 'null');
                                                $alreadyReviewed = $reviewedMap[$key] ?? false;
                                                $review = $reviewDataMap[$key] ?? null;
                                            @endphp

                                            <div class="order-item" data-product-name="{{ $item->product->name ?? '' }}">
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
                                                        {{ $item->product->name ?? 'Sản phẩm không tồn tại' }}
                                                    </h6>
                                                    <p class="product-variant">
                                                        <i class="fas fa-tag me-1"></i>
                                                        @if($item->variant)
                                                            @foreach($item->variant->attributeValues as $attrValue)
                                                                {{ $attrValue->value }} 
                                                            @endforeach
                                                        @endif
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
                                                            method="POST" style="display:none">
                                                            @csrf
                                                        </form>

                                                        <button type="submit" form="refund-cancel-{{ $pending->id }}"
                                                            class="btn btn-outline-warning btn-sm action-btn"
                                                            onclick="return confirm('Bạn có chắc chắn muốn hủy yêu cầu này?');">
                                                            <i class="fas fa-times me-1"></i>Hủy hoàn
                                                        </button>
                                                    @elseif (
                                                        $statusName === 'Đã hoàn thành' &&
                                                            $order->refunds->whereIn('status', ['pending', 'receiving', 'completed'])->count() === 0)
                                                        {{-- Nút tạo yêu cầu hoàn đơn --}}
                                                        <a href="{{ route('refunds.select_items', ['order_id' => $order->id]) }}"
                                                            class="btn btn-outline-warning btn-sm action-btn">
                                                            <i class="fas fa-undo-alt me-1"></i>Hoàn đơn
                                                        </a>
                                                    @endif


                                                    <button class="btn btn-outline-primary btn-sm action-btn reorder-btn">
                                                        <i class="fas fa-redo-alt me-1"></i>Mua lại
                                                    </button>


                                                    <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST" class="d-inline">
    @csrf
    @method('POST')
    <button type="submit" class="btn btn-outline-primary btn-sm action-btn cancel-order-btn" 
            onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
        <i class="fas fa-times-circle me-1"></i>Hủy Đơn
    </button>
</form>
                                                    

                                                    <button class="btn btn-outline-success btn-sm action-btn">
                                                        <i class="fas fa-comments me-1"></i>Chat
                                                    </button>




                                                </div>
                                            </div>
                                        @endforeach


                                    </div>


                                    <!-- Order Footer -->
                                    <div class="order-footer">
                                        <div class="shop-info">
                                            <i class="fas fa-store me-2"></i>
                                            <span class="shop-name">{{ $order->shop->name ?? ' FreshFit.vn' }}</span>
                                        </div>

                                        <div class="order-actions">
                                            @if ($statusName === 'Đã hoàn thành')
                                                @if ($alreadyReviewed)
                                                    <button class="btn btn-outline-warning btn-sm action-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reviewModal-{{ $order->id }}-{{ $item->product->id }}">
                                                        <i class="fas fa-star me-1"></i>Xem Đánh Giá Shop
                                                    </button>
                                                @else
                                                    <button class="btn btn-outline-success btn-sm action-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reviewFormModal-{{ $order->id }}-{{ $item->product->id }}">
                                                        <i class="fas fa-pen me-1"></i>Đánh Giá
                                                    </button>
                                                @endif
                                            @endif
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

                        <!-- No Results Message -->
                        <div class="no-results d-none">
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không tìm thấy đơn hàng nào</h5>
                                <p class="text-muted">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
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
        /* Previous styles remain the same... */

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
            background: none;
            position: relative;
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

        /* Count Badge */
        .count-badge {
            background: rgba(255, 255, 255, 0.3);
            color: inherit;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
            min-width: 20px;
            display: inline-block;
            text-align: center;
        }

        .nav-link.active .count-badge {
            background: rgba(255, 255, 255, 0.9);
            color: #16a34a;
        }

        /* Search Box */
        .search-box {
            max-width: 500px;
            margin: 0 auto;
        }

        .search-box .input-group-text {
            background: #ecf5f4;
            border-color: #d1fae5;
            color: #16a34a;
        }

        .search-box .form-control {
            border-color: #d1fae5;
        }

        .search-box .form-control:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.25);
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

        .order-card.hidden {
            display: none;
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
            color: white;
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
        }

        .status-badge.status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .status-badge.status-processing {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .status-badge.status-completed {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .status-badge.status-cancelled {
            background: linear-gradient(135deg, #ef4444, #dc2626);
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

        /* No Results */
        .no-results {
            background: white;
            border-radius: 20px;
            padding: 60px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-body {
            overflow-y: auto;
            flex: 1 1 auto;
        }

        .modal-footer {
            flex-shrink: 0;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }

        .modal-backdrop.show {
            background-color: transparent !important;
            backdrop-filter: none !important;
            z-index: 1050 !important;
        }

        .modal.show {
            z-index: 1055 !important;
            /* cao hơn mọi thứ khác */
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

        /* Loading animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #16a34a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const filterButtons = document.querySelectorAll('.filter-btn');
            const orderCards = document.querySelectorAll('.order-card');
            const searchInput = document.getElementById('orderSearch');
            const clearSearchBtn = document.getElementById('clearSearch');
            const noResults = document.querySelector('.no-results');

            // Count orders by status
            const statusCounts = {
                all: orderCards.length,
                pending: 0,
                processing: 0,
                completed: 0,
                cancelled: 0
            };

            // Count each status
            orderCards.forEach(card => {
                const status = card.dataset.status;
                if (statusCounts.hasOwnProperty(status)) {
                    statusCounts[status]++;
                }
            });

            // Update count badges
            Object.keys(statusCounts).forEach(status => {
                const badge = document.getElementById(`count-${status}`);
                if (badge) {
                    badge.textContent = statusCounts[status];
                }
            });

            // Filter functionality
            let currentFilter = 'all';
            let currentSearch = '';

            function applyFilters() {
                let visibleCount = 0;

                orderCards.forEach(card => {
                    const cardStatus = card.dataset.status;
                    const orderCode = card.dataset.orderCode.toLowerCase();
                    const productNames = Array.from(card.querySelectorAll('[data-product-name]'))
                        .map(el => el.dataset.productName.toLowerCase())
                        .join(' ');

                    // Check status filter
                    const matchesStatus = currentFilter === 'all' || cardStatus === currentFilter;

                    // Check search filter
                    const matchesSearch = currentSearch === '' ||
                        orderCode.includes(currentSearch) ||
                        productNames.includes(currentSearch);

                    if (matchesStatus && matchesSearch) {
                        card.classList.remove('hidden');
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }

                // Animate visible cards
                setTimeout(() => {
                    const visibleCards = document.querySelectorAll('.order-card:not(.hidden)');
                    visibleCards.forEach((card, index) => {
                        card.style.animationDelay = `${index * 0.1}s`;
                        card.classList.add('fade-in');
                    });
                }, 100);
            }

            // Filter button event listeners
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Update current filter
                    currentFilter = this.dataset.filter;

                    // Apply filters
                    applyFilters();

                    // Add loading effect
                    this.innerHTML = this.innerHTML.replace(/(<i[^>]*><\/i>)/,
                        '$1<span class="loading-spinner ms-2"></span>');

                    setTimeout(() => {
                        const spinner = this.querySelector('.loading-spinner');
                        if (spinner) {
                            spinner.remove();
                        }
                    }, 500);
                });
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                currentSearch = this.value.toLowerCase().trim();
                applyFilters();
            });

            // Clear search
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                currentSearch = '';
                applyFilters();
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + F to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    searchInput.focus();
                }

                // Escape to clear search
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    currentSearch = '';
                    applyFilters();
                    searchInput.blur();
                }
            });

            // Review form functionality
            document.querySelectorAll('.open-review-form').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const productId = this.dataset.productId;
                    const form = document.getElementById(`review-form-${orderId}-${productId}`);

                    if (form) {
                        form.classList.remove('d-none');
                        form.classList.add('active');
                        this.classList.add('d-none');

                        // Scroll to form
                        form.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        // Focus on textarea
                        const textarea = form.querySelector('textarea');
                        if (textarea) {
                            setTimeout(() => textarea.focus(), 500);
                        }
                    }
                });
            });

            // Action button loading states
            document.querySelectorAll('.action-btn:not([type="submit"]):not(.open-review-form)').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.classList.contains('reorder-btn')) {
                        // Special handling for reorder button
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
                        this.disabled = true;

                        // Simulate API call
                        setTimeout(() => {


                            this.innerHTML = '<i class="fas fa-check me-1"></i>Đơn Hàng Đã Hủy';

                            this.classList.remove('btn-outline-primary');
                            this.classList.add('btn-success');

                            setTimeout(() => {
                                this.innerHTML = originalText;
                                this.classList.remove('btn-success');
                                this.classList.add('btn-outline-primary');
                                this.disabled = false;
                            }, 2000);
                        }, 1500);
                    } else {
                        // General loading state
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
                        this.disabled = true;

                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }, 2000);
                    }
                });
            });

            // Smooth scroll for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Auto-refresh order status (optional)
            let refreshInterval;

            function startAutoRefresh() {
                refreshInterval = setInterval(() => {
                    // Check if there are any pending orders
                    const hasPendingOrders = document.querySelector('.order-card[data-status="pending"]');

                    if (hasPendingOrders) {
                        // Show subtle notification
                        showNotification('Đang kiểm tra cập nhật trạng thái đơn hàng...', 'info');

                        // In real app, make AJAX call to check status updates
                        // fetch('/api/orders/status-updates')
                        //     .then(response => response.json())
                        //     .then(data => {
                        //         if (data.updates.length > 0) {
                        //             location.reload();
                        //         }
                        //     });
                    }
                }, 30000); // Check every 30 seconds
            }

            function stopAutoRefresh() {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }
            }

            // Start auto-refresh if there are pending orders
            if (document.querySelector('.order-card[data-status="pending"]')) {
                startAutoRefresh();
            }

            // Stop auto-refresh when page becomes hidden
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopAutoRefresh();
                } else {
                    startAutoRefresh();
                }
            });

            // Notification function
            function showNotification(message, type = 'success') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
                notification.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(notification);

                // Auto-remove after 5 seconds
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }

            // Initialize filters
            applyFilters();

            console.log('Order History initialized with:', {
                totalOrders: orderCards.length,
                statusCounts: statusCounts
            });
            // Hiển thị phần đánh giá đã có khi nhấn "Xem đánh giá"
            document.querySelectorAll('.toggle-review-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const productId = this.dataset.productId;
                    const reviewEl = document.getElementById(`review-${orderId}-${productId}`);
                    if (reviewEl) {
                        reviewEl.classList.toggle('d-none');
                        reviewEl.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });
            });

        });
        document.querySelectorAll('.star-rating').forEach(container => {
            const stars = container.querySelectorAll('.star-icon');
            const input = container.closest('form').querySelector('.rating-input');

            stars.forEach(star => {
                star.addEventListener('mouseenter', function() {
                    const value = parseInt(this.dataset.value);
                    stars.forEach(s => {
                        s.classList.toggle('active', parseInt(s.dataset.value) <= value);
                    });
                });

                star.addEventListener('mouseleave', function() {
                    const selected = parseInt(container.dataset.selected || 0);
                    stars.forEach(s => {
                        s.classList.toggle('active', parseInt(s.dataset.value) <= selected);
                    });
                });

                star.addEventListener('click', function() {
                    const value = parseInt(this.dataset.value);
                    container.dataset.selected = value;
                    input.value = value;
                    stars.forEach(s => {
                        s.classList.toggle('active', parseInt(s.dataset.value) <= value);
                    });
                });
            });
        });
    </script>
@endsection
@section('modals')
    @foreach ($orders as $order)
        @foreach ($order->items as $item)
            @php
                $key = $order->id . '-' . ($item->product->id ?? 'null');
                $review = $reviewDataMap[$key] ?? null;
                $alreadyReviewed = $reviewedMap[$key] ?? false;
            @endphp

            {{-- Modal Đánh giá nếu chưa đánh giá --}}
           @if (!$alreadyReviewed)
    <div class="modal fade" id="reviewFormModal-{{ $order->id }}-{{ $item->product->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <form action="{{ route('client.reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title">Đánh giá sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="d-flex align-items-start gap-3 mb-3">
                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" class="rounded"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <p class="mb-1 text-muted small">
                                    Phân loại: {{ $item->variant->name ?? 'Không phân loại' }}
                                </p>
                                <p class="mb-1 text-muted small">
                                    Giá: {{ number_format($item->price, 0, ',', '.') }}đ
                                </p>
                            </div>
                        </div>


                        <div class="mb-3 d-flex align-items-center gap-2 ms-3 mt-1">
                            @php
                                $reviewUser = $order->user;
                                $avatarPath = $reviewUser?->avatar ?? null;
                            @endphp

                            @if ($avatarPath)
                                <img src="{{ asset('storage/' . $avatarPath) }}" alt="Avatar"
                                     class="rounded-circle border"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Avatar mặc định"
                                     class="rounded-circle border"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @endif

                            <strong>{{ $reviewUser->name ?? 'Ẩn danh' }}</strong>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Đánh giá sao:</label>
                            <div class="d-flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" class="btn-check"
                                        id="star{{ $i }}-{{ $order->id }}-{{ $item->product->id }}"
                                        value="{{ $i }}" required>
                                    <label class="btn btn-outline-warning"
                                        for="star{{ $i }}-{{ $order->id }}-{{ $item->product->id }}">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="review_text">Nội dung đánh giá:</label>
                            <textarea name="review_text" class="form-control" rows="3" required></textarea>
                        </div>


                        <div class="mb-3">
                            <label for="media">Hình ảnh / video:</label>
                            <input type="file" name="media[]" class="form-control" multiple>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-1"></i>Gửi đánh giá
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

            {{-- Modal Xem đánh giá nếu đã đánh giá --}}
            @if ($review)
                <div class="modal fade" id="reviewModal-{{ $order->id }}-{{ $item->product->id }}" tabindex="-1"
                    aria-labelledby="reviewModalLabel-{{ $order->id }}-{{ $item->product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                        <div class="modal-content d-flex flex-column">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="reviewModalLabel-{{ $order->id }}-{{ $item->product->id }}">
                                    Đánh giá sản phẩm
                                </h5>

                            </div>
                            <div class="mb-3">
                                <div class="mb-3 d-flex align-items-center gap-2 ms-3 mt-3">
                                    @php
                                        $reviewUser = $review->user ?? $order->user;
                                        $avatarPath = $reviewUser?->avatar ?? null;
                                    @endphp

                                    @if ($avatarPath)
                                        <img src="{{ asset('storage/' . $avatarPath) }}" alt="Avatar"
                                            class="rounded-circle border"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Avatar mặc định"
                                            class="rounded-circle border"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif

                                    <strong>{{ $reviewUser->name ?? 'Ẩn danh' }}</strong>
                                </div>

                            </div>

                            <div class="modal-body overflow-auto" style="max-height: 70vh;">

                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" class="rounded"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <p class="mb-1 text-muted small">
                                            Phân loại: {{ $item->variant->name ?? 'Không phân loại' }}
                                        </p>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="text-muted small mt-1">
                                            Đánh giá lúc {{ $review->created_at->format('H:i d/m/Y') }}
                                        </div>
                                    </div>
                                </div>




                                @if (!empty($review->title))
                                    <h5 class="fw-bold mb-2">Tiêu đề: {{ $review->title }}</h5>
                                @endif


                                <p class="mb-3">Nội dung: {{ $review->review_text }}</p>

                                <p class="mb-3">Hình ảnh/ Video:
                                    @if ($review->multimedia && $review->multimedia->count())
                                        <div class="d-flex flex-wrap gap-2">

                                            <br>
                                            @foreach ($review->multimedia as $media)
                                                @if (str_starts_with($media->mime_type, 'image/'))
                                                    <img src="{{ asset('storage/' . $media->file) }}"
                                                        class="rounded border"
                                                        style="width: 100px; height: 100px; object-fit: cover;">
                                                @elseif (str_starts_with($media->mime_type, 'video/'))
                                                    <video controls style="width: 120px; height: 100px;">
                                                        <source src="{{ asset('storage/' . $media->file) }}"
                                                            type="{{ $media->mime_type }}">
                                                    </video>
                                                @endif
                                            @endforeach

                                        </div>
                                    @endif
                                </p>
                            </div>

                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endforeach
@endsection
