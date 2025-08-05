@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Chi tiết mã giảm giá #{{ $coupon->id }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.coupon.index') }}">Mã giảm giá</a></li>
                            <li class="active">Chi tiết</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">

        {{-- Thông tin mã giảm giá --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thông tin mã giảm giá</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-2">
                        <strong>Mã:</strong> <div>{{ $coupon->code }}</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Tiêu đề:</strong> <div>{{ $coupon->title }}</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Giảm giá:</strong> <div class="text-danger fw-bold">
                            {{ $coupon->discount_value }} {{ $coupon->discount_type === 'percent' ? '%' : 'VNĐ' }}
                        </div>
                    </div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-md-4 mb-2">
                        <strong>Trạng thái:</strong>
                        <div>
                            <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                {{ $coupon->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Giới hạn:</strong> <div>{{ $coupon->usage_limit ?? 'Không giới hạn' }}</div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Nhóm người dùng:</strong>
                        <div><span class="badge bg-info">{{ $coupon->user_group ?? 'Tất cả' }}</span></div>
                    </div>
                </div>
                <div class="row text-center mt-3">
                    <div class="col-md-6">
                        <strong>Ngày tạo:</strong> <div>{{ $coupon->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Ngày cập nhật:</strong> <div>{{ $coupon->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12"><strong>Mô tả:</strong> {{ $coupon->description ?? 'Không có' }}</div>
                </div>
            </div>
        </div>

        {{-- Thời gian áp dụng --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Thời gian áp dụng</h5>
            </div>
            <div class="card-body row text-center">
                <div class="col-md-3 mb-2"><strong>Bắt đầu:</strong><div>{{ $coupon->start_date ?? '--' }}</div></div>
                <div class="col-md-3 mb-2"><strong>Kết thúc:</strong><div>{{ $coupon->end_date ?? '--' }}</div></div>
                <div class="col-md-3 mb-2"><strong>Thời gian:</strong>
                    <div>
                        <span class="badge bg-{{ $coupon->is_expired ? 'warning' : 'secondary' }}">
                            {{ $coupon->is_expired ? 'Có hạn' : 'Vô hạn' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-3 mb-2"><strong>Đã thông báo:</strong>
                    <div>
                        <span class="badge bg-{{ $coupon->is_notified ? 'primary' : 'light' }}">
                            {{ $coupon->is_notified ? 'Đã gửi' : 'Chưa gửi' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Điều kiện áp dụng --}}
        @if($coupon->restriction)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Điều kiện áp dụng</h5>
            </div>
            <div class="card-body row">
                <div class="col-md-6"><strong>Đơn hàng tối thiểu:</strong> {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} VNĐ</div>
                <div class="col-md-6"><strong>Giảm tối đa:</strong> {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }} VNĐ</div>
            </div>
        </div>
        @endif



        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light"><strong> Danh mục áp dụng</strong></div>
                <div class="card-body">
                    @forelse($categories as $category)
                        <span class="badge bg-info me-1">{{ $category->name }}</span>
                    @empty
                        <span class="text-muted">Không có</span>
                    @endforelse

     

                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Sản phẩm áp dụng</h5>
                    </div>
                    <div class="card-body">
                        @forelse($products as $product)
                            <span class="badge bg-success me-1 mb-1">{{ $product->name }}</span>
                        @empty
                            <span class="text-muted">Không có</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Quay lại --}}
        <div class="text-end mb-4">
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

    </div>
</div>
@endsection
