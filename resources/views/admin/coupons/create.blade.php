@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Tạo mã giảm giá</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.coupon.index') }}">Mã giảm giá</a></li>
                            <li class="active">Tạo mới</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">
        <form action="{{ route('admin.coupon.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <strong>Thông tin chung</strong>
                        </div>
                        <div class="card-body">
                            @foreach ([
                            'code' => 'Mã giảm giá',
                            'title' => 'Tiêu đề',
                            'description' => 'Mô tả',
                            'discount_value' => 'Giá trị giảm',
                            'usage_limit' => 'Giới hạn sử dụng'
                            ] as $field => $label)
                            <div class="form-group">
                                <label>{{ $label }}</label>
                                @if($field === 'description')
                                <textarea name="{{ $field }}" class="form-control">{{ old($field) }}</textarea>
                                @elseif($field === 'discount_value')
                                <input type="number" step="any" name="{{ $field }}" class="form-control" value="{{ old($field) }}">
                                @else
                                <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field) }}">
                                @endif
                                @error($field)
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @endforeach

                            <div class="form-group">
                                <label>Kiểu giảm giá</label>
                                <select name="discount_type" class="form-control">
                                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền</option>
                                </select>
                                @error('discount_type') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-group">
                                <label>Nhóm người dùng</label>
                                <select name="user_group" class="form-control">
                                    <option value="">Tất cả</option>
                                    <option value="guest" {{ old('user_group') == 'guest' ? 'selected' : '' }}>Khách</option>
                                    <option value="member" {{ old('user_group') == 'member' ? 'selected' : '' }}>Thành viên</option>
                                    <option value="vip" {{ old('user_group') == 'vip' ? 'selected' : '' }}>VIP</option>
                                </select>
                                @error('user_group') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <strong>Thời gian & Trạng thái</strong>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Ngày bắt đầu</label>
                                <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
                                @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label>Ngày kết thúc</label>
                                <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_expired" value="1" {{ old('is_expired') ? 'checked' : '' }}>
                                <label class="form-check-label">Có thời hạn</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label">Kích hoạt</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_notified" value="1" {{ old('is_notified') ? 'checked' : '' }}>
                                <label class="form-check-label">Đã thông báo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <strong>Điều kiện áp dụng</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Giá trị đơn hàng tối thiểu</label>
                                    <input type="number" step="any" name="min_order_value" class="form-control" value="{{ old('min_order_value', 0) }}">
                                    @error('min_order_value') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Số tiền giảm tối đa</label>
                                    <input type="number" step="any" name="max_discount_value" class="form-control" value="{{ old('max_discount_value', 0) }}">
                                    @error('max_discount_value') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                               
                                <div class="col-md-6">
                                    <label>Sản phẩm áp dụng</label>
                                    <select name="valid_products[]" class="form-control select2" multiple>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ collect(old('valid_products'))->contains($product->id) ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('valid_products') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-right mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Chọn...'
            , allowClear: true
        });
    });

</script>
@endpush
@endsection
