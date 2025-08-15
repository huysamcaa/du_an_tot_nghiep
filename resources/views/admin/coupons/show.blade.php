@extends('admin.layouts.app')

@section('content')
    <style>
        /* Shopee-like detail layout */
        .sp-card {
            border: 1px solid #eef0f2;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
        }

        .sp-card__hd {
            background: #ffa200;
            color: #fff;
            padding: 12px 16px;
            font-weight: 600;
        }

        .sp-section {
            padding: 0 16px 8px;
        }

        .sp-row {
            display: flex;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #f2f4f7;
            align-items: flex-start;
        }

        .sp-row:last-child {
            border-bottom: none;
        }

        .sp-label {
            width: 220px;
            min-width: 220px;
            color: #334155;
            font-weight: 600;
            padding-top: 4px;
        }

        .sp-value {
            flex: 1;
            color: #0f172a;
        }

        .sp-badges .badge {
            margin: 0 6px 6px 0;
        }

        .sp-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>

    <div class="content">
        <div class="animated fadeIn">

            <div>
                <h4 class="mb-0">Chi tiết mã giảm giá</h4>
                <small class="text-muted">Xem chi tiết mã giảm giá #{{ $coupon->id }}</small>
            </div><br>
            <div class="row g-3">
                {{-- Cột trái --}}
                <div class="col-lg-6">

                    {{-- Thông tin mã giảm giá --}}
                    <div class="sp-card mb-3">
                        <div class="sp-card__hd">Thông tin mã giảm giá</div>
                        <div class="sp-section">
                            <div class="sp-row">
                                <div class="sp-label">Mã</div>
                                <div class="sp-value fw-semibold">{{ $coupon->code }}</div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Tiêu đề</div>
                                <div class="sp-value">{{ $coupon->title }}</div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Giảm giá</div>
                                <div class="sp-value">
                                    <span class="text-danger fw-bold">
                                        {{ $coupon->discount_value }}
                                        {{ $coupon->discount_type === 'percent' ? '%' : 'VNĐ' }}
                                    </span>
                                </div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Trạng thái</div>
                                <div class="sp-value">
                                    <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                        {{ $coupon->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                                    </span>
                                </div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Giới hạn sử dụng</div>
                                <div class="sp-value">{{ $coupon->usage_limit ?? 'Không giới hạn' }}</div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Nhóm người dùng</div>
                                <div class="sp-value">
                                    <span class="badge bg-info">{{ $coupon->user_group ?? 'Tất cả' }}</span>
                                </div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Ngày tạo</div>
                                <div class="sp-value">{{ $coupon->created_at->format('d/m/Y H:i') }}</div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Ngày cập nhật</div>
                                <div class="sp-value">{{ $coupon->updated_at->format('d/m/Y H:i') }}</div>
                            </div>

                            <div class="sp-row">
                                <div class="sp-label">Mô tả</div>
                                <div class="sp-value">{{ $coupon->description ?? 'Không có' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Thời gian áp dụng --}}
                    <div class="sp-card mb-3">
                        <div class="sp-card__hd">Thời gian & thông báo</div>
                        <div class="sp-section">
                            <div class="sp-row">
                                <div class="sp-label">Bắt đầu</div>
                                <div class="sp-value">{{ $coupon->start_date ?? '--' }}</div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Kết thúc</div>
                                <div class="sp-value">{{ $coupon->end_date ?? '--' }}</div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Thời gian</div>
                                <div class="sp-value">
                                    <span class="badge bg-{{ $coupon->is_expired ? 'warning' : 'secondary' }}">
                                        {{ $coupon->is_expired ? 'Có hạn' : 'Vô hạn' }}
                                    </span>
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Thông báo</div>
                                <div class="sp-value">
                                    <span class="badge bg-{{ $coupon->is_notified ? 'primary' : 'light' }}">
                                        {{ $coupon->is_notified ? 'Đã gửi' : 'Chưa gửi' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Cột phải --}}
                <div class="col-lg-6">

                    {{-- Điều kiện áp dụng --}}
                    <div class="sp-card mb-3">
                        <div class="sp-card__hd">Điều kiện áp dụng</div>
                        <div class="sp-section">
                            <div class="sp-row">
                                <div class="sp-label">Đơn hàng tối thiểu</div>
                                <div class="sp-value">
                                    @if ($coupon->restriction)
                                        {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} VNĐ
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Số tiền giảm tối đa</div>
                                <div class="sp-value">
                                    @if ($coupon->restriction && !is_null($coupon->restriction->max_discount_value))
                                        {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }} VNĐ
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Danh mục & Sản phẩm áp dụng --}}
                    <div class="sp-card mb-3">
                        <div class="sp-card__hd">Danh mục áp dụng</div>
                        <div class="sp-section">
                            <div class="sp-row">
                                <div class="sp-label">Danh mục</div>
                                <div class="sp-value sp-badges">
                                    @forelse($categories as $category)
                                        <span class="badge bg-info">{{ $category->name }}</span>
                                    @empty
                                        <span class="text-muted">Không có</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sp-card mb-3">
                        <div class="sp-card__hd">Sản phẩm áp dụng</div>
                        <div class="sp-section">
                            <div class="sp-row">
                                <div class="sp-label">Sản phẩm</div>
                                <div class="sp-value sp-badges">
                                    @forelse($products as $product)
                                        <span class="badge bg-success">{{ $product->name }}</span>
                                    @empty
                                        <span class="text-muted">Không có</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-3 sp-actions">
                <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>

        </div>
    </div>
@endsection
