@extends('admin.layouts.app')

@section('content')
<style>
  /* Shopee-like form layout với 2 cột */
  .sp-card { border: 1px solid #eef0f2; border-radius: 10px; background:#fff; overflow: hidden; margin-bottom: 20px; }
  .sp-card__hd { background:#ffa200; color:#fff; padding:12px 16px; font-weight:600; }
  .sp-section { padding: 0 16px 8px; }
  .sp-row { display:flex; align-items:flex-start; gap:16px; padding:12px 0; border-bottom:1px solid #f2f4f7; }
  .sp-row:last-child { border-bottom:none; }
  .sp-label { width: 160px; min-width:160px; color:#334155; font-weight:600; padding-top:6px; }
  .sp-field { flex:1; }
  .sp-hint { color:#64748b; font-size:12px; margin-top:6px; }
  .sp-actions { position: sticky; bottom: 0; z-index: 5; background:#fff; border-top:1px solid #eef0f2; padding:12px 16px; display:flex; justify-content:flex-end; gap:10px; border-radius:10px; }
  .btn-orange { background:#ffa200; color:#fff; border:none; }
  .btn-orange:hover { background:#e68a00; color:#fff; }
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
    </div>

    <form action="{{ route('admin.coupon.store') }}" method="POST" novalidate>
      @csrf

      <div class="row">
        {{-- Cột trái --}}
        <div class="col-lg-6">
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
                <div class="sp-row">
                  <div class="sp-label">{{ $label }}</div>
                  <div class="sp-field">
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
                </div>
              @endforeach

              <div class="sp-row">
                <div class="sp-label">Kiểu giảm giá</div>
                <div class="sp-field">
                  <select name="discount_type" id="discount_type" class="form-control">
                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                    <option value="fixed"   {{ old('discount_type') == 'fixed'   ? 'selected' : '' }}>Số tiền</option>
                  </select>
                  @error('discount_type') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>

              <div class="sp-row">
                <div class="sp-label">Nhóm người dùng</div>
                <div class="sp-field">
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

        {{-- Cột phải --}}
        <div class="col-lg-6">
          <div class="sp-card mb-3">
            <div class="sp-card__hd">Thời gian & Trạng thái</div>
            <div class="sp-section">
              <div class="sp-row">
                <div class="sp-label">Ngày bắt đầu</div>
                <div class="sp-field">
                  <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                  @error('start_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>
              <div class="sp-row">
                <div class="sp-label">Ngày kết thúc</div>
                <div class="sp-field">
                  <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                  @error('end_date') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>
              <div class="sp-row">
                <div class="sp-label">Tùy chọn</div>
                <div class="sp-field d-flex flex-wrap gap-4">
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
          </div>

          <div class="sp-card">
            <div class="sp-card__hd">Điều kiện áp dụng</div>
            <div class="sp-section">
              <div class="sp-row">
                <div class="sp-label">Giá trị đơn tối thiểu</div>
                <div class="sp-field">
                  <input type="number" step="any" min="0" name="min_order_value" id="min_order_value" class="form-control"
                         value="{{ old('min_order_value', 0) }}">
                  @error('min_order_value') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>
              <div class="sp-row" id="max_discount_value_group">
                <div class="sp-label">Số tiền giảm tối đa</div>
                <div class="sp-field">
                  <input type="number" step="any" min="0" name="max_discount_value" id="max_discount_value" class="form-control"
                         value="{{ old('max_discount_value') }}">
                  <div class="sp-hint">* Chỉ áp dụng khi kiểu giảm giá là phần trăm.</div>
                  @error('max_discount_value') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
              </div>
              <div class="sp-row">
                <div class="sp-label">Sản phẩm áp dụng</div>
                <div class="sp-field">
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
            </div>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="sp-actions">
        <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
          <i class="fa fa-arrow-left"></i> Quay lại
        </a>
        <button type="submit" class="btn btn-orange">
          <i class="fa fa-save"></i> Lưu
        </button>
      </div>

    </form>
  </div>
</div>
@endsection

@push('scripts')
  {{-- Giữ nguyên script hiện có --}}
@endpush
