@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Chỉnh Sửa Mã Giảm Giá</h4>
            <small class="text-muted">Cập nhật mã giảm giá của bạn</small>
        </div>
        <div>
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- TABS HEADER --}}
        <ul class="nav nav-tabs nav-tabs-solid mb-3" id="couponEditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#pane-info" type="button" role="tab" aria-controls="pane-info" aria-selected="true">
                    <i class="fa fa-info-circle me-1"></i> Thông tin
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-time" data-bs-toggle="tab" data-bs-target="#pane-time" type="button" role="tab" aria-controls="pane-time" aria-selected="false">
                    <i class="fa fa-clock me-1"></i> Thời gian & trạng thái
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-conditions" data-bs-toggle="tab" data-bs-target="#pane-conditions" type="button" role="tab" aria-controls="pane-conditions" aria-selected="false">
                    <i class="fa fa-clipboard-check me-1"></i> Điều kiện & phạm vi
                </button>
            </li>
        </ul>

        {{-- TABS CONTENT --}}
        <div class="tab-content" id="couponEditTabsContent">

            {{-- =============== TAB 1: THÔNG TIN =============== --}}
            <div class="tab-pane fade show active" id="pane-info" role="tabpanel" aria-labelledby="tab-info">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-3">
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
                                    <div class="form-group mb-3">
                                        <label class="mb-1">{{ $label }}</label>

                                        @if ($field === 'description')
                                            <textarea name="{{ $field }}" class="form-control" rows="3">{{ old($field, $coupon->$field) }}</textarea>
                                            @error($field) <small class="text-danger">{{ $message }}</small> @enderror

                                        @elseif ($field === 'discount_value')
                                            <input type="number" step="any" min="0" max="99999999.99" name="{{ $field }}" class="form-control"
                                                   value="{{ old($field, $coupon->$field) }}">
                                            @error($field) <small class="text-danger">{{ $message }}</small> @enderror

                                        @elseif ($field === 'usage_limit')
                                            <input type="number" step="1" min="0" name="{{ $field }}" class="form-control"
                                                   value="{{ old($field, $coupon->$field) }}">
                                            @error($field) <small class="text-danger">{{ $message }}</small> @enderror

                                        @else
                                            <input type="text" name="{{ $field }}" class="form-control"
                                                   value="{{ old($field, $coupon->$field) }}">
                                            @error($field) <small class="text-danger">{{ $message }}</small> @enderror
                                        @endif
                                    </div>
                                @endforeach

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Kiểu giảm giá</label>
                                            <select name="discount_type" id="discount_type" class="form-control">
                                                <option value="percent" {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>
                                                    Phần trăm
                                                </option>
                                                <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>
                                                    Số tiền
                                                </option>
                                            </select>
                                            @error('discount_type') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Nhóm người dùng</label>
                                            <select name="user_group" class="form-control">
                                                <option value="">Tất cả</option>
                                                <option value="guest"  {{ old('user_group', $coupon->user_group) == 'guest'  ? 'selected' : '' }}>Khách</option>
                                                <option value="member" {{ old('user_group', $coupon->user_group) == 'member' ? 'selected' : '' }}>Thành viên</option>
                                                <option value="vip"    {{ old('user_group', $coupon->user_group) == 'vip'    ? 'selected' : '' }}>VIP</option>
                                            </select>
                                            @error('user_group') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>

                            </div> {{-- /card-body --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- =============== TAB 2: THỜI GIAN & TRẠNG THÁI =============== --}}
            <div class="tab-pane fade" id="pane-time" role="tabpanel" aria-labelledby="tab-time">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <strong>Thời gian & Trạng thái</strong>
                            </div>
                            <div class="card-body">
                                @php
                                    use Carbon\Carbon;
                                    $startDate = $coupon->start_date ? Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') : '';
                                    $endDate   = $coupon->end_date   ? Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i')   : '';
                                @endphp

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Ngày bắt đầu</label>
                                            <input type="datetime-local" name="start_date" class="form-control"
                                                   value="{{ old('start_date', $startDate) }}">
                                            @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Ngày kết thúc</label>
                                            <input type="datetime-local" name="end_date" class="form-control"
                                                   value="{{ old('end_date', $endDate) }}">
                                            @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap" style="gap:16px;">
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
                            </div>{{-- /card-body --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- =============== TAB 3: ĐIỀU KIỆN & PHẠM VI =============== --}}
            <div class="tab-pane fade" id="pane-conditions" role="tabpanel" aria-labelledby="tab-conditions">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <strong>Điều kiện áp dụng</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="mb-1">Giá trị đơn hàng tối thiểu</label>
                                            <input type="number" step="any" min="0" name="min_order_value" class="form-control"
                                                   value="{{ old('min_order_value', $restriction->min_order_value ?? 0) }}">
                                            @error('min_order_value') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="max_discount_value_group">
                                        <div class="form-group mb-1">
                                            <label class="mb-1">Số tiền giảm tối đa</label>
                                            <input type="number" step="any" min="0" name="max_discount_value" id="max_discount_value" class="form-control"
                                                   value="{{ old('max_discount_value', $restriction->max_discount_value ?? '') }}">
                                            <small class="text-muted">*Chỉ áp dụng khi kiểu giảm là phần trăm</small>
                                        </div>
                                        @error('max_discount_value') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="mb-1">Danh mục áp dụng (tự động theo sản phẩm)</label>
                                        <input type="text" class="form-control"
                                               value="{{ implode(', ', $categories->pluck('name')->toArray()) }}"
                                               readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="mb-1">Sản phẩm áp dụng</label>
                                        <select name="valid_products[]" class="form-control select2" multiple>
                                            @php
                                                $validProducts = collect($restriction->valid_products ?? [])->map(fn($id) => (int)$id)->toArray();
                                            @endphp
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ collect(old('valid_products', $validProducts))->contains($product->id) ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('valid_products') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                            </div>{{-- /card-body --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>{{-- /tab-content --}}

        {{-- ACTIONS --}}
        <div class="mt-3 d-flex justify-content-end" style="gap:10px;">
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select2
            $('.select2').select2({ placeholder: 'Chọn...', allowClear: true });

            // Ngăn nhập ký tự không hợp lệ & kẹp >= 0
            const selectors = [
                'input[name="discount_value"]',
                'input[name="usage_limit"]',
                'input[name="min_order_value"]',
                'input[name="max_discount_value"]',
            ];
            const fields = document.querySelectorAll(selectors.join(','));
            fields.forEach(el => {
                el.addEventListener('keydown', e => { if (['-','e','E','+'].includes(e.key)) e.preventDefault(); });
                el.addEventListener('wheel', e => { if (document.activeElement === el) e.preventDefault(); }, {passive:false});
                const clamp = () => {
                    let v = el.value.trim();
                    if (v === '') return;
                    let num = parseFloat(v);
                    if (isNaN(num) || num < 0) num = 0;
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

            // Ẩn/hiện "Số tiền giảm tối đa" theo kiểu giảm
            const discountTypeSelect = document.querySelector('#discount_type');
            const maxGroup = document.querySelector('#max_discount_value_group');
            const maxField = document.querySelector('#max_discount_value');

            function toggleMaxDiscountField() {
                const isPercent = discountTypeSelect.value === 'percent';
                maxGroup.style.display = isPercent ? 'block' : 'none';
                maxField.disabled = !isPercent;
                if (!isPercent) { maxField.value = ''; maxField.placeholder = 'Không áp dụng'; }
                else { maxField.placeholder = ''; }
            }
            toggleMaxDiscountField();
            discountTypeSelect.addEventListener('change', toggleMaxDiscountField);
        });
    </script>
@endpush
