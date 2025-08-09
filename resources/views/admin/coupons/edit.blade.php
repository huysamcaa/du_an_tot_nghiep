@extends('admin.layouts.app')

@section('content')
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Sửa mã giảm giá</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                <li><a href="{{ route('admin.coupon.index') }}">Mã giảm giá</a></li>
                                <li class="active">Sửa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">
            <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Cột trái -->
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
            'usage_limit' => 'Giới hạn sử dụng',
        ] as $field => $label)
                                    <div class="form-group">
                                        <label>{{ $label }}</label>

                                        @if ($field === 'description')
                                            <textarea name="{{ $field }}" class="form-control">{{ old($field, $coupon->$field) }}</textarea>
                                            @error($field)
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        @elseif ($field === 'discount_value')
                                            <input type="number" step="any" min="0" max="99999999.99"
                                                name="{{ $field }}" class="form-control"
                                                value="{{ old($field, $coupon->$field) }}">
                                            @error($field)
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        @elseif ($field === 'usage_limit')
                                            <input type="number" step="1" min="0" name="{{ $field }}"
                                                class="form-control" value="{{ old($field, $coupon->$field) }}">
                                            @error($field)
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        @else
                                            <input type="text" name="{{ $field }}" class="form-control"
                                                value="{{ old($field, $coupon->$field) }}">
                                            @error($field)
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        @endif
                                    </div>
                                @endforeach


                                <div class="form-group">
                                    <label>Kiểu giảm giá</label>
                                    <select name="discount_type" id="discount_type" class="form-control">
                                        <option value="percent"
                                            {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>
                                            Phần trăm</option>
                                        <option value="fixed"
                                            {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>
                                            Số tiền</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Nhóm người dùng</label>
                                    <select name="user_group" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="guest"
                                            {{ old('user_group', $coupon->user_group) == 'guest' ? 'selected' : '' }}>Khách
                                        </option>
                                        <option value="member"
                                            {{ old('user_group', $coupon->user_group) == 'member' ? 'selected' : '' }}>
                                            Thành Viên</option>
                                        <option value="vip"
                                            {{ old('user_group', $coupon->user_group) == 'vip' ? 'selected' : '' }}>VIP
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cột phải -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <strong>Thời gian & Trạng thái</strong>
                            </div>
                            <div class="card-body">
                                @php
                                    use Carbon\Carbon;
                                    $startDate = $coupon->start_date
                                        ? Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i')
                                        : '';
                                    $endDate = $coupon->end_date
                                        ? Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i')
                                        : '';
                                @endphp

                                <div class="form-group">
                                    <label>Ngày bắt đầu</label>
                                    <input type="datetime-local" name="start_date" class="form-control"
                                        value="{{ old('start_date', $startDate) }}">
                                </div>

                                <div class="form-group">
                                    <label>Ngày kết thúc</label>
                                    <input type="datetime-local" name="end_date" class="form-control"
                                        value="{{ old('end_date', $endDate) }}">
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_expired" value="1"
                                        {{ old('is_expired', $coupon->is_expired) ? 'checked' : '' }}>
                                    <label class="form-check-label">Có thời hạn</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label">Kích hoạt</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_notified" value="1"
                                        {{ old('is_notified', $coupon->is_notified) ? 'checked' : '' }}>
                                    <label class="form-check-label">Đã thông báo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Điều kiện -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <strong>Điều kiện áp dụng</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Giá trị đơn hàng tối thiểu</label>
                                        <input type="number" step="any" min=0 name="min_order_value"
                                            class="form-control"
                                            value="{{ old('min_order_value', $restriction->min_order_value ?? 0) }}">
                                        @error('min_order_value')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6" id="max_discount_value_group">
                                        <label>Số tiền giảm tối đa</label>
                                        <input type="number" step="any" min="0" name="max_discount_value"
                                            id="max_discount_value" class="form-control"
                                           value="{{ old('max_discount_value', $restriction->max_discount_value ?? '') }}">
                                        <small class="text-muted">*Chỉ áp dụng khi kiểu giảm giá là phần trăm</small>
                                        @error('max_discount_value')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>




                                    <div class="col-md-6">
                                        <label>Danh mục áp dụng (tự động theo sản phẩm)</label>
                                        <input type="text" class="form-control"
                                            value="{{ implode(', ', $categories->pluck('name')->toArray()) }}" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Sản phẩm áp dụng</label>
                                        <select name="valid_products[]" class="form-control select2" multiple>
                                            @php
                                                $validProducts = collect($restriction->valid_products ?? [])
                                                    ->map(fn($id) => (int) $id)
                                                    ->toArray();
                                            @endphp
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ collect(old('valid_products', $validProducts))->contains($product->id) ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-right mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </form>
            {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif --}}

        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: 'Chọn...',
                    allowClear: true
                });
                const selectors = [
                    'input[name="discount_value"]',
                    'input[name="usage_limit"]',
                    'input[name="min_order_value"]',
                    'input[name="max_discount_value"]',
                ];
                const fields = document.querySelectorAll(selectors.join(','));

                fields.forEach(el => {
                    // Không cho nhập -, e, E, +
                    el.addEventListener('keydown', (e) => {
                        if (['-', 'e', 'E', '+'].includes(e.key)) e.preventDefault();
                    });

                    // Chặn cuộn chuột khi đang focus (tránh lỡ tay làm âm)
                    el.addEventListener('wheel', (e) => {
                        if (document.activeElement === el) e.preventDefault();
                    }, {
                        passive: false
                    });

                    // Kẹp giá trị về >= 0 sau mọi thay đổi
                    const clamp = () => {
                        let v = el.value.trim();
                        if (v === '') return; // cho phép trống để người dùng tiếp tục nhập
                        let num = parseFloat(v);
                        if (isNaN(num) || num < 0) num = 0;
                        // Tôn trọng min nếu có set khác 0
                        const minAttr = el.getAttribute('min');
                        if (minAttr !== null) {
                            const min = parseFloat(minAttr);
                            if (!isNaN(min) && num < min) num = min;
                        }
                        el.value = num;
                    };

                    el.addEventListener('input', clamp);
                    el.addEventListener('change', clamp);
                    el.addEventListener('blur', clamp);
                });
                // JS đơn giản gắn vào form
                document.querySelector('#discount_type').addEventListener('change', function() {
                    const selected = this.value;
                    const maxDiscountField = document.querySelector('#max_discount_value_group');
                    if (selected === 'percent') {
                        maxDiscountField.style.display = 'block';
                    } else {
                        maxDiscountField.style.display = 'none';
                    }
                });
                const discountTypeSelect = document.querySelector('#discount_type');
                const maxDiscountField = document.querySelector('#max_discount_value');

                function toggleMaxDiscountField() {
                    if (discountTypeSelect.value === 'percent') {
                        maxDiscountField.disabled = false;
                        maxDiscountField.placeholder = '';
                    } else {
                        maxDiscountField.disabled = true;
                        maxDiscountField.value = '';
                        maxDiscountField.placeholder = 'Không áp dụng';
                    }
                }

                toggleMaxDiscountField();
                discountTypeSelect.addEventListener('change', toggleMaxDiscountField);


            });
        </script>
    @endpush
@endsection
