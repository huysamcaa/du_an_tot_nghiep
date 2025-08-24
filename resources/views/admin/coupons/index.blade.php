@extends('admin.layouts.app')

@section('content')
    <div class="content col-md-12">
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

        {{-- Header + nút --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Quản lý mã giảm giá</h4>
                <small class="text-muted">Danh sách mã giảm giá</small>
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

        <div class="card">
            <div class="card-body">
                {{-- FORM LỌC MÃ GIẢM GIÁ --}}
                <form id="couponFilters" method="GET" action="{{ route('admin.coupon.index') }}" class="row g-2 mb-3">
                    {{-- Giữ các tham số khác khi submit (trừ page và các input đã có) --}}
                    @foreach (request()->except(['page']) as $k => $v)
                        @continue(in_array($k, ['search', 'perPage', 'is_active', 'discount_type', 'start_date', 'end_date']))
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach

                    <div class="col-md-3">
                        <label class="form-label mb-1">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tìm mã hoặc tiêu đề..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-1">Trạng thái</label>
                        <select name="is_active" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Đang bật</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Đang tắt</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-1">Loại mã</label>
                        <select name="discount_type" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="percent" {{ request('discount_type') === 'percent' ? 'selected' : '' }}>Phần trăm</option>
                            <option value="fixed" {{ request('discount_type') === 'fixed' ? 'selected' : '' }}>Số tiền</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-1">Ngày bắt đầu</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-1">Ngày kết thúc</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-12 d-flex" style="gap:8px;">
                        <button type="submit" class="btn"
                                style="background:#ffa200;color:#fff;font-weight:600;border:none;border-radius:4px;padding:8px 14px;">
                            Lọc
                        </button>
                        @if (request()->hasAny(['search', 'is_active', 'discount_type', 'start_date', 'end_date']))
                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">Xóa Lọc</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- BULK FORM: xóa hàng loạt --}}
        <form id="bulk-delete-form" method="POST" action="{{ route('admin.coupon.bulkDestroy') }}">
            @csrf
            @method('DELETE')

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="coupon-table" class="table table-bordered table-hover align-middle text-center mb-0">
                            <thead class="table-light">
                            {{-- Thanh công cụ trong thead --}}
                            <tr>
                                {{-- BẢNG 12 CỘT → colspan=12 --}}
                                <th colspan="12" class="p-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                                        {{-- Nút xóa đã chọn (ẩn sẵn, bật khi có chọn) --}}
                                        <div>
                                            <button type="submit" id="btn-bulk-delete"
                                                    class="btn btn-outline-danger btn-sm d-none" title="Xóa đã chọn">
                                                <i class="fa fa-trash"></i> Xóa đã chọn
                                            </button>
                                        </div>
                                    </div>
                                </th>
                            </tr>

                            {{-- Header cột --}}
                            <tr>
                                <th style="width:48px;"><input type="checkbox" id="select-all"></th>
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
                                $groupLabel = [
                                    'guest' => 'Khách',
                                    'member' => 'Thành viên',
                                    'vip' => 'VIP',
                                    null => 'Tất cả',
                                ];
                            @endphp

                            @forelse ($coupons as $coupon)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-check" name="ids[]" value="{{ $coupon->id }}">
                                    </td>

                                    <td>{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}</td>
                                    <td class="fw-semibold">{{ $coupon->code }}</td>
                                    <td class="text-start">{{ $coupon->title }}</td>
                                    <td>
                                        @if ($coupon->discount_type === 'percent')
                                            {{ (int) $coupon->discount_value }}%
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
                                        {{-- Xóa đơn lẻ (nút sẽ submit form ẩn ở ngoài bulk form) --}}
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
                    </div> {{-- .table-responsive --}}
                </div>
            </div>
        </form> {{-- <-- ĐÃ ĐÓNG bulk-delete-form NGAY SAU BẢNG --}}

        {{-- FOOTER CONTROLS: đặt NGOÀI bulk form để không bị submit nhầm --}}
        <div id="brand-footer-controls" class="d-flex justify-content-between align-items-center px-3 py-3"
             style="position:sticky; bottom:0; background:#fff; border-top:1px solid #eef0f2; z-index:5; gap:12px; flex-wrap:wrap;">
            <div>
                {{-- Form hiển thị perPage (đúng route, GET) --}}
                <form method="GET" action="{{ route('admin.coupon.index') }}" class="d-flex align-items-center"
                      style="gap:8px; margin:0;">
                    @foreach (request()->except(['perPage', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach

                    <label for="perPage" class="mb-0" style="font-weight:600;">Hiển thị:</label>
                    <select name="perPage" id="perPage" class="form-control"
                            style="width:90px; border:1px solid #cfd4da; border-radius:8px; padding:6px 10px; background:#f9fafb;"
                            onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $n)
                            <option value="{{ $n }}" {{ request('perPage') == (string) $n ? 'selected' : '' }}>
                                {{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Phải: thống kê + phân trang --}}
            <div class="d-flex align-items-center flex-wrap" style="gap:10px; margin-left:auto;">
                <small class="text-muted me-2">
                    Hiển thị từ {{ $coupons->firstItem() ?? 0 }}
                    đến {{ $coupons->lastItem() ?? 0 }} / {{ $coupons->total() }} mục
                </small>

                <nav aria-label="Pagination">
                    <div class="pagination pagination-sm mb-0">
                        {!! $coupons->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                    </div>
                </nav>
            </div>
        </div>

        {{-- ====== FORM XÓA ĐƠN LẺ ẨN (đặt ngoài bulk form để tránh lồng form) ====== --}}
        @foreach ($coupons as $coupon)
            <form id="delete-coupon-{{ $coupon->id }}" action="{{ route('admin.coupon.destroy', $coupon->id) }}"
                  method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>
@endsection

@section('scripts')

    <script>
        $(function () {
            const table = $('#coupon-table').DataTable({
                order: [[2, 'asc']], // sắp theo cột "Mã"
                paging: false,
                searching: false,
                info: false,
                columnDefs: [
                    { orderable: false, targets: [0, 11] } // checkbox + cột "Hành động"
                ],
                language: {
                    emptyTable: "Không có mã giảm giá nào trong bảng",
                    zeroRecords: "Không tìm thấy mã giảm giá phù hợp"
                }
            });

            // Chọn tất cả
            $('#select-all').on('change', function () {
                const checked = this.checked;
                $('.row-check').prop('checked', checked).trigger('change');
            });

            // Toggle nút "Xóa đã chọn"
            function toggleBulkBtn() {
                const anyChecked = $('.row-check:checked').length > 0;
                $('#btn-bulk-delete').toggleClass('d-none', !anyChecked);
            }

            $(document).on('change', '.row-check', toggleBulkBtn);
            table.on('draw', toggleBulkBtn);
            toggleBulkBtn();

            // Tooltip
            $('[title]').tooltip({ placement: 'top' });
        });
    </script>
@endsection
