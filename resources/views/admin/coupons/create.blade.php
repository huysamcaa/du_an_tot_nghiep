@extends('admin.layouts.app')

@section('content')
<h1>Tạo Mã Giảm Giá</h1>

<form action="{{ route('admin.coupon.store') }}" method="POST">
    @csrf

    <!-- Thông tin cơ bản -->
    <div class="form-group">
        <label>Mã Giảm Giá</label>
        <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
    </div>

    <div class="form-group">
        <label>Tiêu Đề</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
    </div>

    <div class="form-group">
        <label>Mô Tả</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
        <label>Giá Trị Giảm</label>
        <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required>
    </div>

    <div class="form-group">
        <label>Kiểu Giảm Giá</label>
        <select name="discount_type" class="form-control">
            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền</option>
        </select>
    </div>

    <div class="form-group">
        <label>Giới Hạn Sử Dụng</label>
        <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}">
    </div>

    <div class="form-group">
        <label>Nhóm Người Dùng</label>
        <select name="user_group" class="form-control">
            <option value="">Tất cả</option>
            <option value="guest" {{ old('user_group') == 'guest' ? 'selected' : '' }}>Guest</option>
            <option value="member" {{ old('user_group') == 'member' ? 'selected' : '' }}>Member</option>
            <option value="vip" {{ old('user_group') == 'vip' ? 'selected' : '' }}>VIP</option>
        </select>
    </div>

    <div class="form-group">
        <label>Ngày Bắt Đầu</label>
        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
    </div>

    <div class="form-group">
        <label>Ngày Kết Thúc</label>
        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
    </div>

    <!-- Checkboxes -->
    <div class="form-group">
        <label><input type="checkbox" name="is_expired" value="1" {{ old('is_expired') ? 'checked' : '' }}> Có Thời Hạn</label>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}> Kích Hoạt</label>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="is_notified" value="1" {{ old('is_notified') ? 'checked' : '' }}> Đã Thông Báo</label>
    </div>

    <hr>
    <h4>Ràng Buộc</h4>

    <div class="form-group">
        <label>Giá Trị Đơn Hàng Tối Thiểu</label>
        <input type="number" step="0.01" name="min_order_value" class="form-control" value="{{ old('min_order_value', 0) }}">
    </div>

    <div class="form-group">
        <label>Số Tiền Giảm Tối Đa</label>
        <input type="number" step="0.01" name="max_discount_value" class="form-control" value="{{ old('max_discount_value', 0) }}">
    </div>

    <div class="form-group">
        <label>Danh Mục Áp Dụng</label>
        <select name="valid_categories[]" class="form-control select2" multiple>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ collect(old('valid_categories'))->contains($category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Sản Phẩm Áp Dụng</label>
        <select name="valid_products[]" class="form-control select2" multiple>
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ collect(old('valid_products'))->contains($product->id) ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Lưu</button>
</form>
@endsection

@push('scripts')
<!-- Thêm Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Chọn...',
            allowClear: true
        });
    });
</script>
@endpush
