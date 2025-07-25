@extends('admin.layouts.app')

@section('content')

<h1 class="mb-4">Danh Sách Mã Giảm Giá</h1>

<a href="{{ route('admin.coupon.create') }}" class="btn btn-primary mb-4">Thêm Mới</a>
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

<!-- Bộ lọc và tìm kiếm -->
<form method="GET" action="{{ route('admin.coupon.index') }}" class="d-flex justify-content-between mb-3">
    <div>
        <label for="entries">Hiển thị</label>
        <select name="perPage" class="form-control d-inline w-auto" onchange="this.form.submit()">
            <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
        </select> mục
    </div>

    <div>
        <label for="search">Tìm kiếm:</label>
        <input type="text" name="search" class="form-control d-inline w-auto" value="{{ request('search') }}" placeholder="Nhập mã, tiêu đề...">
        <button type="submit" class="btn btn-primary ml-2">Lọc</button>
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
<div class="d-flex justify-content-between mt-3">
    <div>
        <p>Hiển thị {{ $coupons->firstItem() }} đến {{ $coupons->lastItem() }} của {{ $coupons->total() }} mã giảm giá</p>
    </div>
    <div>{{ $coupons->links('pagination::bootstrap-4') }}</div>
</div>

@endsection
