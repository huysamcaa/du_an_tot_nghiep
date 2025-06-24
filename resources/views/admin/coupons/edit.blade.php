@extends('admin.layouts.app')

@section('content')
<h1>Sửa Mã Giảm Giá</h1>

<form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Mã giảm giá -->
    <div class="form-group">
        <label for="code">Mã Giảm Giá</label>
        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
        @error('code')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Tiêu đề -->
    <div class="form-group">
        <label for="title">Tiêu Đề</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $coupon->title) }}" required>
        @error('title')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Giá trị giảm -->
    <div class="form-group">
        <label for="discount_value">Giảm Giá</label>
        <input type="number" class="form-control" id="discount_value" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" required>
        @error('discount_value')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Kiểu giảm giá -->
    <div class="form-group">
        <label for="discount_type">Kiểu Giảm Giá</label>
        <select name="discount_type" id="discount_type" class="form-control">
            <option value="percent" {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
        </select>
    </div>

    <!-- Kích hoạt -->
    <div class="form-group">
        <label for="is_active">Kích Hoạt</label>
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
    </div>

    <!-- Các ràng buộc nếu có -->
    @if (isset($restriction))
        <hr>
        <h4>Ràng Buộc Mã Giảm Giá</h4>

        <div class="form-group">
            <label for="min_order_value">Giá Trị Đơn Hàng Tối Thiểu</label>
            <input type="number" class="form-control" name="min_order_value" value="{{ old('min_order_value', $restriction->min_order_value) }}">
        </div>

        <div class="form-group">
            <label for="max_discount_value">Số Tiền Giảm Tối Đa</label>
            <input type="number" class="form-control" name="max_discount_value" value="{{ old('max_discount_value', $restriction->max_discount_value) }}">
        </div>

        <div class="form-group">
            <label for="valid_categories">Danh Mục Áp Dụng (ID, cách nhau bởi dấu phẩy)</label>
            <input type="text" class="form-control" name="valid_categories" value="{{ old('valid_categories', implode(',', json_decode($restriction->valid_categories, true) ?? [])) }}">
        </div>

        <div class="form-group">
            <label for="valid_products">Sản Phẩm Áp Dụng (ID, cách nhau bởi dấu phẩy)</label>
            <input type="text" class="form-control" name="valid_products" value="{{ old('valid_products', implode(',', json_decode($restriction->valid_products, true) ?? [])) }}">
        </div>
    @endif

    <button type="submit" class="btn btn-primary">Cập Nhật</button>
</form>
@endsection
