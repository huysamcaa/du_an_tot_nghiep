@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->code)

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2 class="animate__animated animate__fadeInDown">Lịch Sử Mua Hàng</h2>
                    <div class="pageBannerPath animate__animated animate__fadeInUp">
                        <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Lịch Sử Mua Hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="order-status-tracking py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div class="mb-3 mb-md-0 text-center text-md-start">
                                <h6 class="text-muted mb-1">Mã đơn hàng</h6>
                                <h5 class="fw-bolder text-primary mb-0 animate__animated animate__fadeInLeft">#{{ $order->code }}</h5>
                            </div>

                            <div class="flex-grow-1 px-md-4 mt-4 mt-md-0">
                                <div class="timeline-wrapper">
                                    <div class="timeline">
                                        @php
                                            $statuses = [
                                                1 => ['icon' => 'fas fa-shopping-cart', 'text' => 'Chờ xác nhận', 'color' => 'text-info'],
                                                2 => ['icon' => 'fas fa-check-circle', 'text' => 'Đã xác nhận', 'color' => 'text-primary'],
                                                3 => ['icon' => 'fas fa-box', 'text' => 'Đóng gói', 'color' => 'text-warning'],
                                                4 => ['icon' => 'fas fa-truck', 'text' => 'Vận chuyển', 'color' => 'text-secondary'],
                                                5 => ['icon' => 'fas fa-clipboard-check', 'text' => 'Hoàn Thành', 'color' => 'text-success'], // Changed icon and text for clarity
                                                6 => ['icon' => 'fas fa-times-circle', 'text' => 'Đã hủy', 'color' => 'text-danger'] // Changed icon for clarity
                                            ];

                                            $currentStatusId = $order->currentStatus->order_status_id;
                                            // Adjust progress bar for 5 active steps + cancelled as a separate state
                                            $progressBarWidth = ($currentStatusId <= 5) ? (($currentStatusId / 5) * 100) : 100;
                                        @endphp

                                        <div class="timeline-progress">
                                            <div class="timeline-progress-bar" style="width: {{ $progressBarWidth }}%"></div>
                                        </div>

                                        <div class="timeline-items">
                                            @foreach($statuses as $id => $status)
                                                @if ($id <= 5) {{-- Only show active progression statuses --}}
                                                <div class="timeline-item {{ $id <= $currentStatusId ? 'active' : '' }} {{ $id == $currentStatusId ? 'current' : '' }} animate__animated animate__fadeIn">
                                                    <div class="timeline-icon {{ $status['color'] }}">
                                                        <i class="{{ $status['icon'] }}"></i>
                                                    </div>
                                                    <div class="timeline-text">
                                                        <span>{{ $status['text'] }}</span>
                                                       
                                                            <small class="d-block text-muted-darker animate__animated animate__fadeIn">
                                                                @if($order->currentStatus->created_at)
                                                                    {{ $order->currentStatus->created_at->format('H:i d/m/Y') }}
                                                                
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 mt-md-0 text-center text-md-end animate__animated animate__fadeInRight">
                                <span class="badge {{ $order->status_class }} py-2 px-3 fs-6 rounded-pill">
                                    {{ $order->status_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-6 animate__animated animate__fadeInLeft">
            <div class="card border-0 shadow-lg h-100 rounded-3">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-user-circle me-2"></i>Thông tin người đặt
                    </h5>
                </div>
                <div class="card-body pt-0">
                    @if($order->user)
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-2">
                        @if($order->user->avatar)
                            <img src="{{ asset('storage/' . $order->user->avatar) }}"
                                 class="rounded-circle me-3 border border-primary p-1"
                                 width="70"
                                 height="70"
                                 alt="Avatar">
                        @else
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 70px; height: 70px;">
                                <i class="fas fa-user fs-3"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1 text-dark">{{ $order->user->fullname ?? $order->user->name }}</h6>
                            <small class="text-muted">Thành viên từ: {{ $order->user->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endif

                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Tài khoản:</span>
                            <span class="text-dark">{{ $order->user->email ?? 'Khách vãng lai' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Điện thoại:</span>
                            <span class="text-dark">{{ $order->user->phone_number ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Giới tính:</span>
                            <span class="text-dark">
                                @if($order->user && $order->user->gender)
                                    @if($order->user->gender === 'male')
                                        Nam
                                    @elseif($order->user->gender === 'female')
                                        Nữ
                                    @else
                                        Khác
                                    @endif
                                @else
                                    Không xác định
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Ngày sinh:</span>
                            <span class="text-dark">{{ $order->user->birthday ? \Carbon\Carbon::parse($order->user->birthday)->format('d/m/Y') : 'Không có' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Điểm tích lũy:</span>
                            <span class="text-dark">{{ $order->user->loyalty_points ?? 0 }} điểm</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6 animate__animated animate__fadeInRight">
            <div class="card border-0 shadow-lg h-100 rounded-3">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-truck-moving me-2"></i>Thông tin người nhận
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Mã đơn hàng:</span>
                            <span class="fw-bold text-dark">#{{ $order->code }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Người nhận:</span>
                            <span class="text-dark">{{ $order->fullname }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Điện thoại:</span>
                            <span class="text-dark">{{ $order->phone_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Email:</span>
                            <span class="text-dark">{{ $order->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Địa chỉ:</span>
                            <span class="text-end text-dark">{{ $order->address }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Ngày đặt:</span>
                            <span class="text-dark">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="fw-medium text-secondary">Trạng thái thanh toán:</span>
                             <span class="badge {{ $order->is_paid == 1 ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 text-{{ $order->is_paid == 1 ? 'success' : 'secondary' }} rounded-pill py-2 px-3">
                                @if($order->is_paid == 1 )
                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                @elseif($order->is_paid == 0)
                                    <i class="fas fa-clock me-1"></i>Thanh toán khi nhận hàng
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item border-0 px-0 pt-2 pb-0">
                            <div class="d-flex">
                                <span class="fw-medium text-secondary me-2">Ghi chú:</span>
                                <span class="text-dark">{{ $order->note ?? 'Không có ghi chú' }}</span>
                            </div>
                        </li>
                        @if($order->currentStatus->order_status_id == 6)
                            <li class="list-group-item border-0 px-0 pt-2 pb-0">
                                <div class="d-flex align-items-center bg-danger-subtle text-danger p-2 rounded-2 mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span class="fw-medium">Đơn hàng đã hủy</span>
                                    <span>{{ $order->currentStatus->notes ?? 'Không có ghi chú hủy' }}</span>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4 animate__animated animate__fadeInUp">
            <div class="card border-0 shadow-lg rounded-3">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-shopping-basket me-2"></i>Chi tiết sản phẩm
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Sản phẩm</th>
                                    <th class="text-end py-3">Đơn giá</th>
                                    <th class="text-center py-3">Số lượng</th>
                                    <th class="text-end pe-4 py-3">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="item-row">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 product-thumbnail-wrapper">
                                                @if($item->product && $item->product->thumbnail)
                                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                         alt="{{ $item->name }}"
                                                         class="img-fluid product-thumbnail">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center product-thumbnail-placeholder">
                                                        <i class="fas fa-image text-muted fs-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-dark">{{ $item->name }}</h6>
                                                <small class="text-muted product-variant-info">
                                                    @if ($item->variant)
                                                        @php
                                                            $color = $item->variant->attributeValues->firstWhere('attribute.name', 'Color')->value ?? '';
                                                            $size = $item->variant->attributeValues->firstWhere('attribute.name', 'Size')->value ?? '';
                                                        @endphp
                                                        @if($color) Màu: {{ $color }} @endif
                                                        @if($color && $size) | @endif
                                                        @if($size) Size: {{ $size }} @endif
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end py-3 text-dark price-cell">
                                        @if($item->variant)
                                            {{ number_format($item->variant->price) }}₫
                                        @else
                                            {{ number_format($item->product->price) }}₫
                                        @endif
                                    </td>
                                    <td class="text-center py-3 text-dark">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 py-3 fw-bold text-primary">{{ number_format($item->price * $item->quantity) }}₫</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4 py-3">Tổng tiền hàng</td>
                                    <td class="text-end fw-bold pe-4 py-3 text-dark">{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; })) }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4 py-3">Phí vận chuyển</td>
                                    <td class="text-end fw-bold pe-4 py-3 text-dark">30.000₫</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4 py-3">Mã giảm giá đã dùng</td>
                                    <td class="text-end fw-bold pe-4 py-3 text-success">
                                        @if($order->coupon_code)
                                            Mã: {{ $order->coupon_code }} (Giảm: {{ number_format($order->coupon_discount_value, 0, ',', '.') }}₫)
                                        @else
                                            Không có
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-4 py-3 fs-5">Tổng thanh toán</td>
                                    <td class="text-end fw-bolder text-danger pe-4 py-3 fs-5">{{ number_format($order->total_amount) }}₫</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('client.orders.purchase.history') }}" class="btn btn-outline-primary px-4 py-2 rounded-pill shadow-sm animate__animated animate__pulse">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <a href="{{ route('client.home') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow animate__animated animate__pulse">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Google Fonts - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #ffffff; /* Light gray background */
    }

    /* General Card Styling */
    .card {
        border-radius: 15px; /* More rounded corners */
        overflow: hidden;
        margin-bottom: 25px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.08); /* Slightly stronger border */
        background-color: #fff;
        padding: 1.25rem 1.5rem; /* More padding */
    }

    .card-header h5 {
        color: #007bff; /* Primary blue for headings */
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .card-header i {
        color: #007bff; /* Primary blue for icons */
    }

    .list-group-item {
        padding: 12px 0;
        font-size: 0.95rem;
        border-color: rgba(0,0,0,.05) !important;
    }

    .list-group-item span {
        color: #495057; /* Darker text for information */
    }

    .list-group-item .fw-medium {
        color: #6c757d; /* Lighter color for labels */
    }

    /* Banner Section */
    .pageBannerSection {
        background-color: #ecf5f4; /* Lighter background for banner */
        padding: 80px 0; /* More padding */
        margin-bottom: 40px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        box-shadow: inset 0 -5px 10px rgba(0, 0, 0, 0.05);
    }

    .pageBannerContent h2 {
        font-size: 3rem; /* Larger font size */
        font-weight: 700;
        color: #343a40; /* Darker text */
        margin-bottom: 10px;
    }

    .pageBannerPath {
        color: #6c757d; /* Muted color for path */
        font-size: 1rem;
    }

    .pageBannerPath a {
        color: #007bff; /* Primary color for links */
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .pageBannerPath a:hover {
        color: #0056b3; /* Darker primary on hover */
    }

    /* Order Status Tracking (Shopee Style) */
    .order-status-tracking {
        background-color: #f0f2f5; /* Very light background for this section */
        padding: 3rem 0;
    }

    .timeline-wrapper {
        position: relative;
        padding: 0 20px;
        margin-top: 20px;
    }

    .timeline {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: flex-start; /* Align items to the top for better text display */
    }

    .timeline-progress {
        height: 4px; /* Thicker progress bar */
        background-color: #e0e0e0;
        position: absolute;
        top: 30px; /* Adjust based on icon size */
        left: 0;
        right: 0;
        z-index: 1;
        border-radius: 2px;
    }

    .timeline-progress-bar {
        height: 100%;
        background-color: #28a745; /* Green for completion */
        transition: width 0.5s ease-in-out; /* Smoother transition */
        border-radius: 2px;
    }

    .timeline-items {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        width: 100%;
    }

    .timeline-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
        padding: 0 5px; /* Add some padding to prevent overlap on smaller screens */
        min-width: 100px; /* Ensure minimum width for each item */
    }

    .timeline-icon {
        width: 50px; /* Larger icon size */
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border: 3px solid #ced4da; /* Lighter border */
        margin-bottom: 10px;
        font-size: 22px; /* Larger icon font */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .timeline-item.active .timeline-icon {
        border-color: #007bff; /* Primary color for active border */
        color: #007bff; /* Primary color for active icon */
    }

    .timeline-item.current .timeline-icon {
        background-color: #007bff; /* Primary color for current background */
        color: white;
        border-color: #007bff;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); /* Stronger shadow for current */
        transform: scale(1.1); /* Slight enlarge effect */
    }

    .timeline-text {
        text-align: center;
        font-size: 0.85rem;
        color: #757575;
        line-height: 1.3;
    }

    .timeline-item.active .timeline-text,
    .timeline-item.current .timeline-text {
        color: #343a40; /* Darker text for active/current */
        font-weight: 500;
    }

    .timeline-text small {
        display: block;
        font-size: 0.75rem;
        color: #999;
        margin-top: 3px;
    }

    .text-muted-darker {
        color: #555 !important;
    }

    /* Product table styling */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        background-color: #f8f9fa !important;
        color: #343a40;
        border-bottom: 1px solid rgba(0,0,0,.08);
    }

    .table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .item-row:hover {
        background-color: #9ebbbd; /* Light hover effect for table rows */
    }

    .product-thumbnail-wrapper {
        width: 70px; /* Slightly larger thumbnails */
        height: 70px;
        overflow: hidden;
        border-radius: 10px; /* Rounded corners for thumbnails */
        border: 1px solid #eee;
        flex-shrink: 0;
    }

    .product-thumbnail {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }

    .product-thumbnail-placeholder {
        height: 100%;
        width: 100%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 1.5rem;
    }

    .product-variant-info {
        font-size: 0.8rem;
        color: #888;
    }

    .price-cell {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* A more "number-friendly" font */
        font-weight: 600;
    }

    /* Badges */
    .badge {
        padding: 0.5em 0.9em; /* More padding */
        font-weight: 600;
        font-size: 0.9rem; /* Slightly larger font */
        border-radius: 50px; /* Pill shape */
        min-width: 120px; /* Ensure consistent width */
        text-align: center;
    }

    /* Payment Status Badges */
    .badge.bg-success.bg-opacity-10 {
        background-color: rgba(40, 167, 69, 0.15) !important; /* Brighter green opacity */
        color: #28a745 !important;
    }

    .badge.bg-secondary.bg-opacity-10 {
        background-color: rgba(108, 117, 125, 0.15) !important; /* Brighter gray opacity */
        color: #6c757d !important;
    }

    /* Buttons */
    .btn {
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-primary {
        border-color: #9ebbbd;
        color: #9ebbbd;
    }

    .btn-outline-primary:hover {
        background-color: #4a8286;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    }

    .btn-primary {
        background-color: #9ebbbd;
        border-color: #9ebbbd;
    }

    .btn-primary:hover {
        background-color: #4a8286;
        border-color: #4a8286;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.4);
    }

    /* Animations (requires Animate.css, if not already linked) */
    /* Add this to your main layout file or via CDN */
    /* <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/> */

    /* Custom classes for status colors */
    .text-info { color: #17a2b8 !important; }
    .text-primary { color: #007bff !important; }
    .text-warning { color: #ffc107 !important; }
    .text-secondary { color: #6c757d !important; }
    .text-success { color: #28a745 !important; }
    .text-danger { color: #dc3545 !important; }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .pageBannerSection {
            padding: 40px 0;
            margin-bottom: 25px;
        }

        .pageBannerContent h2 {
            font-size: 2.2rem;
        }

        .order-status-tracking .card-body {
            padding: 1.5rem;
        }

        .timeline-wrapper {
            padding: 0;
            margin-top: 20px;
        }

        .timeline-items {
            flex-wrap: wrap;
            justify-content: center; /* Center items when wrapped */
        }

        .timeline-item {
            flex: 0 0 50%; /* 2 items per row */
            margin-bottom: 20px;
        }

        .timeline-progress {
            display: none; /* Hide progress bar on mobile for stacked layout */
        }

        .timeline-item:nth-child(2n) {
            border-right: none; /* No vertical line on every second item */
        }

        /* Adjustments for the status badge and order code alignment on mobile */
        .order-status-tracking .d-flex {
            text-align: center;
        }
        .order-status-tracking .mb-3.mb-md-0,
        .order-status-tracking .mt-3.mt-md-0 {
            width: 100%;
        }

        .table thead {
            display: none; /* Hide table header on small screens */
        }

        .table, .table tbody, .table tr, .table td {
            display: block; /* Make table elements behave like blocks */
            width: 100%;
        }

        .table tr {
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .table td {
            text-align: right !important;
            padding-left: 50% !important; /* Space for the pseudo-element label */
            position: relative;
            border: none;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .table td::before {
            content: attr(data-label); /* Use data-label for content */
            position: absolute;
            left: 0;
            width: 50%;
            padding-left: 1rem;
            font-weight: 600;
            text-align: left;
            color: #6c757d;
        }

        /* Specific data-labels for table cells */
        .table tbody td:nth-child(1)::before { content: 'Sản phẩm:'; }
        .table tbody td:nth-child(2)::before { content: 'Đơn giá:'; }
        .table tbody td:nth-child(3)::before { content: 'Số lượng:'; }
        .table tbody td:nth-child(4)::before { content: 'Thành tiền:'; }

        /* Footer total rows */
        .table tfoot tr {
            border: none;
            box-shadow: none;
        }
        .table tfoot td {
            text-align: right !important;
            padding-left: 1rem !important;
        }
        .table tfoot td:nth-child(1)::before { content: ''; } /* Hide pseudo-element for footer */
    }

    @media (max-width: 576px) {
        .timeline-item {
            flex: 0 0 100%; /* 1 item per row on very small screens */
        }
    }
</style>

@endsection