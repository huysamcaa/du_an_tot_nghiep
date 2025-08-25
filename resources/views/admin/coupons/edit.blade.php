@extends('admin.layouts.app')

@section('content')
<style>
  .sp-card { border:1px solid #eef0f2;border-radius:10px;background:#fff;overflow:hidden;margin-bottom:20px; }
  .sp-card__hd { background:#ffa200;color:#fff;padding:12px 16px;font-weight:600; }
  .sp-section { padding:12px 16px; }
  .btn-orange { background:#ffa200;color:#fff;border:none; }
  .btn-orange:hover { background:#e68a00;color:#fff; }
</style>

<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Chỉnh Sửa Mã Giảm Giá</h4>
      <small class="text-muted">Cập nhật mã giảm giá của bạn</small>
    </div>
    {{-- Bỏ nút quay lại ở header; nút sẽ nằm ở góc phải cột sticky --}}
  </div>
<div class="card border-0 shadow-sm mx-n2 mx-lg-n3">
      <div class="card-body px-3 px-lg-4">

  <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    <div class="row">
      {{-- CỘT TRÁI: THÔNG TIN CHUNG --}}
      <div class="col-lg-8">
        <div class="sp-card">
          <div class="sp-card__hd">Thông tin chung</div>
          <div class="sp-section">
            @foreach ([
              'code' => 'Mã giảm giá',
              'title' => 'Tiêu đề',
              'description' => 'Mô tả',
              'discount_value' => 'Giá trị giảm',
              'usage_limit' => 'Giới hạn sử dụng',
            ] as $field => $label)
              <div class="mb-3">
                <label class="mb-1">{{ $label }}</label>

                @if ($field === 'description')
                  <textarea name="{{ $field }}" class="form-control" rows="3">{{ old($field, $coupon->$field) }}</textarea>
                  @error($field) <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror

                @elseif ($field === 'discount_value')
                  <input type="number" step="any" min="0" max="99999999.99" name="{{ $field }}" class="form-control" value="{{ old($field, $coupon->$field) }}">
                  @error($field) <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror

                @elseif ($field === 'usage_limit')
                  <input type="number" step="1" min="0" name="{{ $field }}" class="form-control" value="{{ old($field, $coupon->$field) }}">
                  @error($field) <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror

                @else
                  <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field, $coupon->$field) }}">
                  @error($field) <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                @endif
              </div>
            @endforeach

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="mb-1">Kiểu giảm giá</label>
                  <select name="discount_type" id="discount_type" class="form-control">
                    <option value="percent" {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                    <option value="fixed"   {{ old('discount_type', $coupon->discount_type) == 'fixed'   ? 'selected' : '' }}>Số tiền</option>
                  </select>
                  @error('discount_type') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="mb-1">Nhóm người dùng</label>
                  <select name="user_group" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="guest"  {{ old('user_group', $coupon->user_group) == 'guest'  ? 'selected' : '' }}>Khách</option>
                    <option value="member" {{ old('user_group', $coupon->user_group) == 'member' ? 'selected' : '' }}>Thành viên</option>
                    <option value="vip"    {{ old('user_group', $coupon->user_group) == 'vip'    ? 'selected' : '' }}>VIP</option>
                  </select>
                  @error('user_group') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      {{-- CỘT PHẢI: STICKY + ACTION BUTTONS + THỜI GIAN/TRẠNG THÁI + ĐIỀU KIỆN --}}
      <div class="col-lg-4">
        <div class="sticky-top" style="top: 100px;">



          {{-- Thời gian & Trạng thái --}}
          <div class="sp-card">
            <div class="sp-card__hd">Thời gian & Trạng thái</div>
            <div class="sp-section">
              @php
                use Carbon\Carbon;
                $startDate = $coupon->start_date ? Carbon::parse($coupon->start_date)->format('Y-m-d\\TH:i') : '';
                $endDate   = $coupon->end_date   ? Carbon::parse($coupon->end_date)->format('Y-m-d\\TH:i')   : '';
              @endphp

              <div class="mb-3">
                <label class="mb-1">Ngày bắt đầu</label>
                <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                @error('start_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
              </div>

              <div class="mb-3">
                <label class="mb-1">Ngày kết thúc</label>
                <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                @error('end_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
              </div>

              <div class="d-flex flex-wrap" style="gap:16px;">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_expired" value="1" {{ old('is_expired', $coupon->is_expired) ? 'checked' : '' }}>
                  <label class="form-check-label">Có thời hạn</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                  <label class="form-check-label">Kích hoạt</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_notified" value="1" {{ old('is_notified', $coupon->is_notified) ? 'checked' : '' }}>
                  <label class="form-check-label">Đã thông báo</label>
                </div>
              </div>
            </div>
          </div>

          {{-- Điều kiện áp dụng --}}
          <div class="sp-card">
            <div class="sp-card__hd">Điều kiện áp dụng</div>
            <div class="sp-section">
              <div class="row">
                <div class="col-12">
                  <div class="mb-3">
                    <label class="mb-1">Giá trị đơn hàng tối thiểu</label>
                    <input type="number" step="any" min="0" name="min_order_value" class="form-control" value="{{ old('min_order_value', $restriction->min_order_value ?? 0) }}">
                    @error('min_order_value') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>

                <div class="col-12" id="max_discount_value_group">
                  <div class="mb-1">
                    <label class="mb-1">Số tiền giảm tối đa</label>
                    <input type="number" step="any" min="0" name="max_discount_value" id="max_discount_value" class="form-control" value="{{ old('max_discount_value', $restriction->max_discount_value ?? '') }}">
                    <small class="text-muted">* Chỉ áp dụng khi kiểu giảm là phần trăm</small>
                  </div>
                  @error('max_discount_value') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-12 mb-3">
                  <label class="mb-1">Danh mục áp dụng (tự động theo sản phẩm)</label>
                  <input type="text" class="form-control" value="{{ implode(', ', $categories->pluck('name')->toArray()) }}" readonly>
                </div>

                <div class="col-12">
                  <label class="mb-1">Sản phẩm áp dụng</label>
                  <select name="valid_products[]" class="form-control select2" multiple>
                    @php
                      $validProducts = collect($restriction->valid_products ?? [])->map(fn($id) => (int)$id)->toArray();
                    @endphp
                    @foreach ($products as $product)
                      <option value="{{ $product->id }}" {{ collect(old('valid_products', $validProducts))->contains($product->id) ? 'selected' : '' }}>
                        {{ $product->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('valid_products') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>

            </div>
             {{-- Nút hành động ở góc phải --}}
          <div class="text-end mb-3">
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary me-2">
              <i class="fa fa-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-orange me-2">
              <i class="fa fa-save"></i> Cập nhật
            </button>
          </div>
          </div>

        </div>
      </div>
      {{-- /Cột phải --}}

    </div>
      </div>
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
      if (window.jQuery && typeof jQuery.fn.select2 === 'function') {
        jQuery('.select2').select2({ placeholder: 'Chọn...', allowClear: true, width: '100%' });
      }

      // Ngăn nhập ký tự không hợp lệ & kẹp >= 0
      const selectors = [
        'input[name="discount_value"]',
        'input[name="usage_limit"]',
        'input[name="min_order_value"]',
        'input[name="max_discount_value"]',
      ];
      document.querySelectorAll(selectors.join(',')).forEach(el => {
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
      const discountTypeSelect = document.getElementById('discount_type');
      const maxGroup = document.getElementById('max_discount_value_group');
      const maxField = document.getElementById('max_discount_value');
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
