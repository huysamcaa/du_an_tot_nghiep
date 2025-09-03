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
  <div class="animated fadeIn">

    {{-- Alerts --}}
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="mb-0">Thêm Mã giảm giá</h4>
        <small class="text-muted">Tạo mới mã giảm giá</small>
      </div>
      {{-- bỏ nút quay lại ở đây để gom vào góc phải sticky --}}
    </div>
    <div class="card border-0 shadow-sm mx-n2 mx-lg-n3">
      <div class="card-body px-3 px-lg-4">

    <form action="{{ route('admin.coupon.store') }}" method="POST" novalidate>
      @csrf

      <div class="row">
        {{-- Cột trái: Thông tin chung (giống trang sản phẩm) --}}
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
                    <textarea name="{{ $field }}" class="form-control" rows="3">{{ old($field) }}</textarea>
                  @elseif($field === 'discount_value')
                    <input type="number" min="0" step="any" name="{{ $field }}" class="form-control" value="{{ old($field) }}">
                  @elseif($field === 'usage_limit')
                    <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field) }}">
                  @else
                    <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field) }}">
                  @endif
                  @error($field) <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              @endforeach

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="mb-1">Kiểu giảm giá</label>
                    <select name="discount_type" id="discount_type" class="form-control">
                      <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                      <option value="fixed"   {{ old('discount_type') == 'fixed'   ? 'selected' : '' }}>Số tiền</option>
                    </select>
                    @error('discount_type') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="mb-1">Nhóm người dùng</label>
                    <select name="user_group" class="form-control">
                      <option value="">Tất cả</option>
                      <option value="guest"  {{ old('user_group') == 'guest'  ? 'selected' : '' }}>Khách</option>
                      <option value="member" {{ old('user_group') == 'member' ? 'selected' : '' }}>Thành viên</option>
                      <option value="vip"    {{ old('user_group') == 'vip'    ? 'selected' : '' }}>VIP</option>
                    </select>
                    @error('user_group') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        {{-- Cột phải: Sticky + nút action ở góc phải --}}
        <div class="col-lg-4">
          <div class="sticky-top" style="top: 100px;">

            {{-- Nút hành động góc phải --}}


            {{-- Thời gian & Trạng thái --}}
            <div class="sp-card">
              <div class="sp-card__hd">Thời gian & Trạng thái</div>
              <div class="sp-section">
                <div class="mb-3">
                  <label class="mb-1">Ngày bắt đầu</label>
                  <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                  @error('start_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                  <label class="mb-1">Ngày kết thúc</label>
                  <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                  @error('end_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <div class="d-flex flex-wrap gap-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_expired" value="1" id="is_expired" {{ old('is_expired') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_expired">Có thời hạn</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Kích hoạt</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_notified" value="1" id="is_notified" {{ old('is_notified') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_notified">Đã thông báo</label>
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
                      <label class="mb-1">Giá trị đơn tối thiểu</label>
                      <input type="number" step="any" min="0" name="min_order_value" id="min_order_value" class="form-control" value="{{ old('min_order_value', 0) }}">
                      @error('min_order_value') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>
                  </div>

                  <div class="col-12" id="max_discount_value_group">
                    <div class="mb-1">
                      <label class="mb-1">Số tiền giảm tối đa</label>
                      <input type="number" step="any" min="0" name="max_discount_value" id="max_discount_value" class="form-control" value="{{ old('max_discount_value') }}">
                      <small class="text-muted">* Chỉ áp dụng khi kiểu giảm giá là phần trăm</small>
                    </div>
                    @error('max_discount_value') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                  </div>
                </div>

                <div class="mb-3">
                  <label class="mb-1">Sản phẩm áp dụng</label>
                  <select name="valid_products[]" class="form-control select2" multiple>
                    @foreach ($products as $product)
                      <option value="{{ $product->id }}" {{ collect(old('valid_products'))->contains($product->id) ? 'selected' : '' }}>
                        {{ $product->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('valid_products') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

              </div>
              <div class="text-end mb-3">
              <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fa fa-arrow-left"></i> Quay lại
              </a>
              <button type="submit" class="btn btn-orange me-2">
                <i class="fa fa-save"></i> Lưu
              </button>
            </div>
            </div>

          </div>

        {{-- /Cột phải --}}
      </div>
      </div>
    </form>
     </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Ngăn nhập ký tự không hợp lệ & kẹp >= 0
    const numeric = ['discount_value','min_order_value','max_discount_value'];
    numeric.forEach(name => {
      const el = document.querySelector(`[name="${name}"]`);
      if (!el) return;
      el.addEventListener('keydown', e => { if (['-','e','E','+'].includes(e.key)) e.preventDefault(); });
      el.addEventListener('wheel', e => { if (document.activeElement === el) e.preventDefault(); }, {passive:false});
      const clamp = () => {
        if (el.value.trim() === '') return;
        let v = parseFloat(el.value);
        if (isNaN(v) || v < 0) v = 0;
        const minAttr = el.getAttribute('min');
        if (minAttr !== null) {
          const min = parseFloat(minAttr);
          if (!isNaN(min) && v < min) v = min;
        }
        el.value = v;
      };
      el.addEventListener('input', clamp);
      el.addEventListener('change', clamp);
      el.addEventListener('blur', clamp);
    });

    // Ẩn/hiện "Số tiền giảm tối đa" khi đổi kiểu giảm giá
    const typeSel = document.getElementById('discount_type');
    const maxGroup = document.getElementById('max_discount_value_group');
    const maxField = document.getElementById('max_discount_value');
    function toggleMax() {
      const isPercent = typeSel.value === 'percent';
      maxGroup.style.display = isPercent ? 'block' : 'none';
      maxField.disabled = !isPercent;
      if (!isPercent) { maxField.value = ''; maxField.placeholder = 'Không áp dụng'; }
      else { maxField.placeholder = ''; }
    }
    toggleMax();
    typeSel.addEventListener('change', toggleMax);

    // Khởi tạo select2 nếu có
    if (window.jQuery && typeof jQuery.fn.select2 === 'function') {
      jQuery('.select2').select2({ placeholder:'Chọn...', allowClear:true, width: '100%' });
    }
    // Ràng buộc "Có thời hạn" <-> ngày bắt đầu/kết thúc
const chkTime = document.getElementById('is_expired');
const startEl = document.getElementById('start_date');
const endEl   = document.getElementById('end_date');

function applyTimeLimitUI() {
  if (!chkTime || !startEl || !endEl) return;
  const on = chkTime.checked;
  [startEl, endEl].forEach(el => {
    el.disabled = !on;
    el.required =  on;
    if (!on) el.value = '';
  });
}

if (chkTime) {
  chkTime.addEventListener('change', applyTimeLimitUI);
}
// Nếu người dùng nhập ngày -> auto tick
[startEl, endEl].forEach(el => {
  if (!el) return;
  el.addEventListener('input', () => {
    if ((startEl.value || endEl.value) && !chkTime.checked) {
      chkTime.checked = true;
    }
    applyTimeLimitUI();
  });
});
applyTimeLimitUI();

  });
</script>
@endpush
