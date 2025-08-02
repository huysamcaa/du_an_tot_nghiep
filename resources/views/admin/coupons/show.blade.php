@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-ticket-alt me-2"></i>Chi tiết Mã Giảm Giá</h2>

    <div class="row g-4">


        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light"><strong>Thông tin mã</strong></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr><th>Mã</th><td>{{ $coupon->code }}</td></tr>
                        <tr><th>Tiêu đề</th><td>{{ $coupon->title }}</td></tr>
                        <tr><th>Mô tả</th><td>{{ $coupon->description ?? 'Không có' }}</td></tr>
                        <tr><th>Giảm giá</th>
                            <td class="text-danger fw-bold">
                                {{ $coupon->discount_value }} {{ $coupon->discount_type === 'percent' ? '%' : 'VNĐ' }}
                            </td>
                        </tr>
                        <tr><th>Giới hạn sử dụng</th><td>{{ $coupon->usage_limit ?? 'Không giới hạn' }}</td></tr>
                        <tr>
                            <th>Nhóm người dùng</th>
                            <td><span class="badge bg-info text-dark">{{ $coupon->user_group ?? 'Tất cả' }}</span></td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td><span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                {{ $coupon->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                            </span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light"><strong>Thời gian áp dụng</strong></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr><th>Bắt đầu</th><td>{{ $coupon->start_date ?? '--' }}</td></tr>
                        <tr><th>Kết thúc</th><td>{{ $coupon->end_date ?? '--' }}</td></tr>
                        <tr><th>Trạng thái thời gian</th>
                            <td>
                                <span class="badge bg-{{ $coupon->is_expired ? 'warning' : 'secondary' }}">
                                    {{ $coupon->is_expired ? 'Có hạn' : 'Vô hạn' }}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Đã thông báo</th>
                            <td>
                                <span class="badge bg-{{ $coupon->is_notified ? 'primary' : 'light' }}">
                                    {{ $coupon->is_notified ? 'Đã gửi' : 'Chưa gửi' }}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Ngày tạo</th><td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td></tr>
                        <tr><th>Ngày cập nhật</th><td>{{ $coupon->updated_at->format('d/m/Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>


        @if($coupon->restriction)
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light"><strong>Điều kiện áp dụng</strong></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr>
                            <th>Đơn hàng tối thiểu</th>
                            <td>{{ number_format($coupon->restriction->min_order_value, 0, ',', '.') ?? 'Không' }} VNĐ</td>
                        </tr>
                        <tr>
                            <th>Giảm tối đa</th>
                            <td>{{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') ?? 'Không giới hạn' }} VNĐ</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif


        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light"><strong> Danh mục áp dụng</strong></div>
                <div class="card-body">
                    @forelse($categories as $category)
                        <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                    @empty
                        <span class="text-muted">Không có</span>
                    @endforelse
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light"><strong>Sản phẩm áp dụng</strong></div>
                <div class="card-body">
                    @forelse($products as $product)
                        <span class="badge bg-success me-1">{{ $product->name }}</span>
                    @empty
                        <span class="text-muted">Không có</span>
                    @endforelse
                </div>
            </div>
        </div>


        <div class="col-12 text-end mt-4">
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

    </div>
</div>
@endsection
