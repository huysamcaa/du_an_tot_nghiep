@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Quản lý mã giảm giá</h4>
            <h6>Danh sách mã giảm giá</h6>
        </div>
        <div class="mb-3 d-flex" style="gap:10px;">
            <a href="{{ route('admin.coupon.create') }}"
               style="background-color:#ffa200;color:#fff;border:none;padding:8px 15px;border-radius:6px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;"
               onmouseover="this.style.backgroundColor='#e68a00'" onmouseout="this.style.backgroundColor='#ffa200'">
                <i class="fa fa-plus"></i> Thêm mã giảm giá
            </a>

            <a href="{{ route('admin.coupon.trashed') }}"
               style="background-color:#ffa200;color:#fff;border:none;padding:8px 15px;border-radius:6px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;"
               onmouseover="this.style.backgroundColor='#e68a00'" onmouseout="this.style.backgroundColor='#ffa200'">
                <i class="fa fa-trash"></i> Mã giảm giá đã xóa
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    {{-- MỘT CARD CHỨA CẢ FORM LỌC + BẢNG --}}
    <div class="card">
        <div class="card-body">

            {{-- FORM LỌC (GET) --}}
          <form id="couponFilters" method="GET" action="{{ route('admin.coupon.index') }}" class="row g-2 mb-3">
    {{-- Giữ tham số khác --}}
    @foreach (request()->except(['page']) as $k => $v)
        @continue(in_array($k, ['search', 'perPage', 'is_active', 'discount_type', 'start_date', 'end_date']))
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach

    <div class="col-12 col-lg-2">
        <label class="form-label mb-1">Tìm kiếm</label>
        <input type="text" name="search" class="form-control" placeholder="Tìm mã hoặc tiêu đề..." value="{{ request('search') }}">
    </div>

    <div class="col-6 col-lg-1">
        <label class="form-label mb-1">Trạng thái</label>
        <select name="is_active" class="form-control" onchange="this.form.submit()">
            <option value="">-- Tất cả --</option>
            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang bật</option>
            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Đang tắt</option>
        </select>
    </div>

    <div class="col-6 col-lg-1">
        <label class="form-label mb-1">Loại mã</label>
        <select name="discount_type" class="form-control" onchange="this.form.submit()">
            <option value="">-- Tất cả --</option>
            <option value="percent" {{ request('discount_type') === 'percent' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed"   {{ request('discount_type') === 'fixed'   ? 'selected' : '' }}>Số tiền</option>
        </select>
    </div>

    <div class="col-6 col-lg-2">
        <label class="form-label mb-1">Ngày bắt đầu</label>
        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>

    <div class="col-6 col-lg-2">
        <label class="form-label mb-1">Ngày kết thúc</label>
        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>

    <div class="col-6 col-lg-1">
        <label class="form-label mb-1">Hiển thị</label>
        <select name="perPage" class="form-control" onchange="this.form.submit()">
            @foreach ([10,25,50,100] as $n)
                <option value="{{ $n }}" {{ request('perPage', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
            @endforeach
        </select>
    </div>

    {{-- Cụm nút: Tìm kiếm + Xóa lọc (cùng 1 dòng) --}}
  <div class="col-12 col-lg-auto d-flex align-items-end">
  <div class="d-flex" style="gap:8px;">
    <button type="submit"
            class="btn"
            style="background:#ffa200;color:#fff;font-weight:600;border:none;border-radius:4px;flex:0 0 auto;white-space:nowrap;">
      Tìm kiếm
    </button>

    @if (request()->hasAny(['search','is_active','discount_type','start_date','end_date','perPage']))
      <a href="{{ route('admin.coupon.index') }}"
         class="btn btn-outline-secondary"
         style="flex:0 0 auto;white-space:nowrap;">
        Xóa lọc
      </a>
    @endif
  </div>
</div>


</form>



            {{-- BULK DELETE (POST) + BẢNG --}}
            <form id="bulk-delete-form" method="POST" action="{{ route('admin.coupon.bulkDestroy') }}">
                @csrf
                @method('DELETE')

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0 ">
                        <thead class="table-light">
                        {{-- Thanh công cụ trong thead --}}
                        <tr>
                            <th colspan="12" class="p-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                                    <div>
                                        <button type="submit" id="btn-bulk-delete"
                                                class="btn btn-outline-danger btn-sm d-none" title="Xóa đã chọn">
                                            <i class="fa fa-trash"></i> Xóa đã chọn
                                        </button>
                                    </div>
                                </div>
                            </th>
                        </tr>

                        {{-- Header --}}
                        <tr>
                            <th style="width:30px;"><input type="checkbox" id="select-all"></th>
                            <th>STT</th>
                            <th>Mã</th>
                            <th>Tiêu đề</th>
                            <th>Giảm</th>
                            <th>Nhóm</th>
                            <th>Sử dụng</th>
                            <th>Thời hạn</th>
                            <th>Kích hoạt</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php
                            $groupLabel = ['guest'=>'Khách','member'=>'Thành viên','vip'=>'VIP',null=>'Tất cả'];
                        @endphp
                        @forelse($coupons as $coupon)
                            <tr>
                                <td><input type="checkbox" class="row-check" name="ids[]" value="{{ $coupon->id }}"></td>
                                <td>{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}</td>
                                <td class="fw-semibold">{{ $coupon->code }}</td>
                                <td class="text-start">{{ $coupon->title }}</td>
                                <td>
                                    @if ($coupon->discount_type === 'percent')
                                        {{ (int)$coupon->discount_value }}%
                                    @else
                                        {{ number_format($coupon->discount_value, 0, ',', '.') }} đ
                                    @endif
                                </td>
                                <td>{{ $groupLabel[$coupon->user_group] ?? 'Tất cả' }}</td>
                                <td>{{ $coupon->usage_count ?? 0 }}/{{ $coupon->usage_limit ?? '∞' }}</td>
                                <td>
                                    @if ($coupon->is_expired)
                                        <span class="badge bg-warning text-dark">Có hạn</span>
                                    @else
                                        <span class="badge bg-secondary">Vô hạn</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($coupon->is_active)
                                        <span class="badge bg-success">Bật</span>
                                    @else
                                        <span class="badge bg-secondary">Tắt</span>
                                    @endif
                                </td>
                                <td>{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y H:i') : '--' }}</td>
                                <td>{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y H:i') : '--' }}</td>
                                <td>
                                    <a href="{{ route('admin.coupon.show', $coupon->id) }}"
                                       class="btn btn-sm btn-outline-info" title="Xem">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}"
                                       class="btn btn-sm btn-outline-warning" title="Sửa">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    {{-- Xóa đơn lẻ: submit form ẩn bên dưới --}}
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                            form="delete-coupon-{{ $coupon->id }}"
                                            onclick="return confirm('Xác nhận xóa mã này?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">Không có mã giảm giá nào.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer trong cùng card --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Hiển thị từ {{ $coupons->firstItem() ?? 0 }} đến {{ $coupons->lastItem() ?? 0 }} trên tổng số {{ $coupons->total() }} mã
                    </div>
                    <div>
                        {!! $coupons->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </form>

            {{-- Form xóa đơn lẻ ẩn (KHÔNG lồng trong bulk form) --}}
            @foreach ($coupons as $coupon)
                <form id="delete-coupon-{{ $coupon->id }}" action="{{ route('admin.coupon.destroy', $coupon->id) }}"
                      method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach

        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
/* Ẩn spinner số trên Chrome/Safari/Edge */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
/* Ẩn spinner số trên Firefox */
input[type=number] { -moz-appearance: textfield; appearance: textfield; }
</style>
<script>
(function () {
    // Chọn tất cả
    const selectAll = document.getElementById('select-all');
    const bulkBtn   = document.getElementById('btn-bulk-delete');

    function toggleBulkBtn () {
        const anyChecked = document.querySelectorAll('.row-check:checked').length > 0;
        if (bulkBtn) bulkBtn.classList.toggle('d-none', !anyChecked);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = selectAll.checked);
            toggleBulkBtn();
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList && e.target.classList.contains('row-check')) toggleBulkBtn();
    });

    toggleBulkBtn();
})();
</script>
@endpush
