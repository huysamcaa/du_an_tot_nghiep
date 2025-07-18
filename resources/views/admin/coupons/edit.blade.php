@extends('admin.layouts.app')

@section('content')
<h1>Sửa Mã Giảm Giá</h1>

@php
    use Carbon\Carbon;

    $startDate = $coupon->start_date ? Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') : '';
    $endDate = $coupon->end_date ? Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') : '';

    // Ép kiểu để đảm bảo dữ liệu là mảng số nguyên
    $validCategories = collect($restriction->valid_categories ?? [])->map(fn($id) => (int) $id)->toArray();
    $validProducts = collect($restriction->valid_products ?? [])->map(fn($id) => (int) $id)->toArray();
@endphp

<form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Thông tin cơ bản -->
    <div class="form-group">
        <label>Mã Giảm Giá</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
    </div>

    <div class="form-group">
        <label>Tiêu Đề</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title) }}" required>
    </div>

    <div class="form-group">
        <label>Mô Tả</label>
        <textarea name="description" class="form-control">{{ old('description', $coupon->description) }}</textarea>
    </div>

    <div class="form-group">
        <label>Giá Trị Giảm</label>
        <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" required>
    </div>

    <div class="form-group">
        <label>Kiểu Giảm Giá</label>
        <select name="discount_type" class="form-control">
            <option value="percent" {{ $coupon->discount_type == 'percent' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Số tiền</option>
        </select>
    </div>

    <div class="form-group">
        <label>Giới Hạn Sử Dụng</label>
        <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}">
    </div>

    <div class="form-group">
        <label>Nhóm Người Dùng</label>
        <select name="user_group" class="form-control">
            <option value="">Tất cả</option>
            <option value="guest" {{ $coupon->user_group == 'guest' ? 'selected' : '' }}>Guest</option>
            <option value="member" {{ $coupon->user_group == 'member' ? 'selected' : '' }}>Member</option>
            <option value="vip" {{ $coupon->user_group == 'vip' ? 'selected' : '' }}>VIP</option>
        </select>
    </div>

    <div class="form-group">
        <label>Ngày Bắt Đầu</label>
        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
    </div>

    <div class="form-group">
        <label>Ngày Kết Thúc</label>
        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
    </div>

    <!-- Checkboxes -->
    <div class="form-group">
        <label><input type="checkbox" name="is_expired" value="1" {{ $coupon->is_expired ? 'checked' : '' }}> Có Thời Hạn</label>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }}> Kích Hoạt</label>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_notified" value="1" {{ $coupon->is_notified ? 'checked' : '' }}> Đã Thông Báo</label>
    </div>

    <hr>
    <h4>Ràng Buộc</h4>

    <div class="form-group">
        <label>Giá Trị Đơn Hàng Tối Thiểu</label>
        <input type="number" name="min_order_value" class="form-control" value="{{ old('min_order_value', $restriction->min_order_value ?? 0) }}">
    </div>

    <div class="form-group">
        <label>Số Tiền Giảm Tối Đa</label>
        <input type="number" name="max_discount_value" class="form-control" value="{{ old('max_discount_value', $restriction->max_discount_value ?? 0) }}">
    </div>

    <div class="form-group">
        <label>Danh Mục Áp Dụng</label>
        <select name="valid_categories[]" class="form-control" multiple>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, $validCategories) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Sản Phẩm Áp Dụng</label>
        <select name="valid_products[]" class="form-control" multiple>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" {{ in_array($product->id, $validProducts) ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Cập Nhật</button>
</form>
@endsection
