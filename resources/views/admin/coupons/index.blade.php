@extends('admin.layouts.app')

@section('content')

<h1 class="mb-4">Danh Sách Mã Giảm Giá</h1>

<a href="{{ route('admin.coupon.create') }}" class="btn btn-primary mb-4">Thêm Mới Mã Giảm Giá</a>
<a href="{{ route('admin.coupon.trashed') }}" class="btn btn-secondary mb-4">Mã Đã Xóa</a>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Thanh Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item d-flex justify-content-between w-100">
            <span>Admin</span>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Trang chủ</a>
                <a href="{{ route('admin.coupon.index') }}" class="breadcrumb-item">Khuyến mãi</a>
                <span class="breadcrumb-item active">Danh Sách Mã Giảm Giá</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Bộ lọc hiển thị + tìm kiếm giống Brand -->
<form method="GET" action="{{ route('admin.coupon.index') }}" class="row g-2 align-items-center mb-4">
    {{-- Số lượng hiển thị --}}
    <div class="col-auto">
        <label for="entries" class="form-label mb-0">Hiển thị</label>
        <select name="perPage" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="10" {{ request('perPage', 10) == '10' ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
        </select>
    </div>
</form>

{{-- Form tìm kiếm --}}
<form method="GET" action="{{ route('admin.coupon.index') }}" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        @if(request('search'))
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-times me-1"></i> Xóa
            </a>
        @endif
    </div>
</form>



@php
    $groupLabel = [
        'guest' => 'Khách',
        'member' => 'Thành viên',
        'vip' => 'VIP',
        null => 'Tất cả'
    ];
@endphp

<!-- Danh sách mã giảm giá -->
<table class="table table-bordered table-striped table-hover">
    <thead class="">
        <tr>
            <th>STT</th>
            <th>Mã</th>
            <th>Tiêu đề</th>
            <th>Giảm</th>
            <th>Nhóm</th>
            <th>Sử dụng</th>
            <th>Thời hạn</th>
            <th>Kích hoạt</th>
            <th>Thông báo</th>
            <th>Bắt đầu</th>
            <th>Kết thúc</th>
            <th>Ngày tạo</th>
            <th>Ngày sửa</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($coupons as $coupon)
        <tr>
            <td>{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}</td>
            <td>{{ $coupon->code }}</td>
            <td>{{ $coupon->title }}</td>
            <td>
                @if ($coupon->discount_type === 'percent')
                    {{ (int) $coupon->discount_value }}%
                @else
                    {{ number_format($coupon->discount_value, 0, ',', '.') }} VNĐ

                @endif
            </td>
            <td>{{ $groupLabel[$coupon->user_group] ?? 'Tất cả' }}</td>
            <td>{{ $coupon->usage_count ?? 0 }}/{{ $coupon->usage_limit ?? '∞' }}</td>
            <td>
                <span class="badge badge-{{ $coupon->is_expired ? 'warning' : 'secondary' }}">
                    {{ $coupon->is_expired ? 'Có hạn' : 'Vô hạn' }}
                </span>
            </td>
            <td>
                <span class="badge badge-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                    {{ $coupon->is_active ? 'Bật' : 'Tắt' }}
                </span>
            </td>
            <td>
                <span class="badge badge-{{ $coupon->is_notified ? 'info' : 'light' }}">
                    {{ $coupon->is_notified ? 'Đã gửi' : 'Chưa gửi' }}
                </span>
            </td>
            <td>{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y H:i') : '--' }}</td>
            <td>{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y H:i') : '--' }}</td>
            <td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $coupon->updated_at->format('d/m/Y H:i') }}</td>
            <td>
                 <a href="{{ route('admin.coupon.show', $coupon->id) }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.coupon.destroy', $coupon->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa mã này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Phân trang -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
        Hiển thị từ {{ $coupons->firstItem() ?? 0 }} đến {{ $coupons->lastItem() ?? 0 }} trên tổng số {{ $coupons->total() }} mã
    </div>

    <div>
        @if ($coupons->hasPages())
            {!! $coupons->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
        @else
            {{-- Giữ bố cục nhất quán dù không có nhiều trang --}}
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item active"><span class="page-link">1</span></li>
                </ul>
            </nav>
        @endif
    </div>
</div>

@endsection
<style>
    .pagination {
        display: flex !important;
    }
</style>
