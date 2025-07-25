@extends('admin.layouts.app')

@section('content')
<h1>Tạo Mã Giảm Giá</h1>

{{-- Hiển thị tất cả lỗi --}}
{{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}

<form action="{{ route('admin.coupon.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Mã Giảm Giá</label>
        <input type="text" name="code" class="form-control" value="{{ old('code') }}" >
        @error('code')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Tiêu Đề</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}" >
        @error('title')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Mô Tả</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Giá Trị Giảm</label>
        <input type="number" name="discount_value" class="form-control" step="1" value="{{ old('discount_value') }}">
        @error('discount_value')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Kiểu Giảm Giá</label>
        <select name="discount_type" class="form-control">
            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền</option>
        </select>
        @error('discount_type')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Giới Hạn Sử Dụng</label>
        <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}">
        @error('usage_limit')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Nhóm Người Dùng</label>
        <select name="user_group" class="form-control">
            <option value="">Tất cả</option>
            <option value="guest" {{ old('user_group') == 'guest' ? 'selected' : '' }}>Guest</option>
            <option value="member" {{ old('user_group') == 'member' ? 'selected' : '' }}>Member</option>
            <option value="vip" {{ old('user_group') == 'vip' ? 'selected' : '' }}>VIP</option>
        </select>
        @error('user_group')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Ngày Bắt Đầu</label>
        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
        @error('start_date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Ngày Kết Thúc</label>
        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
        @error('end_date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

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
        @error('min_order_value')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label>Số Tiền Giảm Tối Đa</label>
        <input type="number" step="0.01" name="max_discount_value" class="form-control" value="{{ old('max_discount_value', 0) }}">
        @error('max_discount_value')
            <small class="text-danger">{{ $message }}</small>
        @enderror
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
        @error('valid_categories')
            <small class="text-danger">{{ $message }}</small>
        @enderror
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
        @error('valid_products')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Lưu</button>
</form>
@endsection

@push('scripts')
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
