@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-ticket-alt me-2"></i>Chi tiết Mã Giảm Giá</h2>

    <div class="card shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
    <strong class="text-dark">Mã: {{ $coupon->code }}</strong>
    <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
        {{ $coupon->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
    </span>
</div>


        <div class="card-body row g-4">
            <div class="col-md-6">
                <p><strong>Tiêu đề:</strong> {{ $coupon->title }}</p>
                <p><strong>Mô tả:</strong> {{ $coupon->description ?? 'Không có' }}</p>
                <p><strong>Giảm giá:</strong>
                    <span class="text-danger fw-bold">
                        {{ $coupon->discount_value }} {{ $coupon->discount_type === 'percent' ? '%' : 'VNĐ' }}
                    </span>
                </p>
                <p><strong>Giới hạn sử dụng:</strong> {{ $coupon->usage_limit ?? 'Không giới hạn' }}</p>
                <p><strong>Nhóm người dùng:</strong>
                    <span class="badge bg-info text-dark">
                        {{ $coupon->user_group ?? 'Tất cả' }}
                    </span>
                </p>
            </div>

            <div class="col-md-6">
                <p><strong>Thời gian áp dụng:</strong></p>
                <p>
                    Bắt đầu: <span class="text-primary">{{ $coupon->start_date ?? '--' }}</span><br>
                    Kết thúc: <span class="text-primary">{{ $coupon->end_date ?? '--' }}</span>
                </p>
                <p><strong>Trạng thái thời gian:</strong>
                    <span class="badge bg-{{ $coupon->is_expired ? 'warning' : 'secondary' }}">
                        {{ $coupon->is_expired ? 'Có hạn' : 'Vô hạn' }}
                    </span>
                </p>
                <p><strong>Thông báo người dùng:</strong>
                    <span class="badge bg-{{ $coupon->is_notified ? 'primary' : 'light' }}">
                        {{ $coupon->is_notified ? 'Đã gửi' : 'Chưa gửi' }}
                    </span>
                </p>
                <p><strong>Ngày tạo:</strong> {{ $coupon->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Ngày cập nhật:</strong> {{ $coupon->updated_at->format('d/m/Y H:i') }}</p>
            </div>

            @if($coupon->restriction)
                <div class="col-12">
                    <hr>
                    <h5 class="mb-3">🔒 Điều kiện áp dụng</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Đơn hàng tối thiểu:</strong>
                            {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') ?? 'Không' }} VNĐ
                        </li>
                        <li class="list-group-item">
                            <strong>Giảm tối đa:</strong>
                            {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') ?? 'Không giới hạn' }} VNĐ
                        </li>
                        <li class="list-group-item">
                            <strong>Danh mục áp dụng:</strong>
                            @forelse($categories as $category)
                                <span class="badge  me-1">{{ $category->name }}</span>
                            @empty
                                <span class="text-muted">Không có</span>
                            @endforelse
                        </li>
                        <li class="list-group-item">
                            <strong>Sản phẩm áp dụng:</strong>
                            @forelse($products as $product)
                                <span class="badge me-1">{{ $product->name }}</span>
                            @empty
                                <span class="text-muted">Không có</span>
                            @endforelse
                        </li>
                    </ul>
                </div>
            @endif
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
                <i class="fas  me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection
