@extends('admin.layouts.app')

@section('content')
    <div class="content">
        <div class="animated fadeIn">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0">Mã Giảm Giá Đã Xóa</h4>
                    <small class="text-muted">Danh sách mã giảm giá đã xóa</small>
                </div>
                <div>
                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>

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

            {{-- BULK RESTORE FORM --}}
            <form id="bulk-restore-form" method="POST" action="{{ route('admin.coupon.bulkRestore') }}">
                @csrf

                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-striped table-bordered text-center align-middle mb-0">
                            <thead>
                                {{-- Thanh công cụ trên cùng --}}
                                <tr>
                                    {{-- BẢNG 6 CỘT → colspan=6 --}}
                                    <th colspan="6" class="p-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap"
                                            style="gap:12px;">
                                            <div class="d-flex align-items-center" style="gap:8px;">
                                                <button type="submit" id="btn-bulk-restore"
                                                    class="btn btn-outline-success btn-sm" disabled>
                                                    <i class="fa fa-undo"></i> Khôi phục mục đã chọn
                                                </button>
                                            </div>
                                            {{-- chừa chỗ nếu sau muốn thêm tìm kiếm/ lọc --}}
                                            <div></div>
                                        </div>
                                    </th>
                                </tr>

                                {{-- Header cột --}}
                                <tr>
                                    <th style="width:48px;">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>#</th>
                                    <th>Mã</th>
                                    <th>Tiêu đề</th>
                                    <th>Ngày xóa</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="row-check" name="ids[]"
                                                value="{{ $coupon->id }}">
                                        </td>
                                        <td>{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}
                                        </td>
                                        <td class="fw-semibold">{{ $coupon->code }}</td>
                                        <td class="text-start">{{ $coupon->title }}</td>
                                        <td>{{ $coupon->deleted_at ? $coupon->deleted_at->format('d/m/Y H:i') : '--' }}</td>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                formaction="{{ route('admin.coupon.restore', $coupon->id) }}"
                                                formmethod="POST" onclick="return confirm('Khôi phục mã này?')">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Không có mã giảm giá nào đã bị
                                            xóa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </form>

            {{-- Footer phân trang + thống kê --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Hiển thị từ {{ $coupons->firstItem() ?? 0 }} đến {{ $coupons->lastItem() ?? 0 }}
                    / {{ $coupons->total() }} mã
                </small>

                <nav aria-label="Pagination">
                    <div class="pagination pagination-sm mb-0">
                        @if ($coupons->hasPages())
                            {!! $coupons->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                        @else
                            <ul class="pagination mb-0">
                                <li class="page-item active"><span class="page-link">1</span></li>
                            </ul>
                        @endif
                    </div>
                </nav>
            </div>

        </div>
    </div>

    {{-- JS nhỏ cho tích chọn --}}
    @push('scripts')
        <script>
            (function() {
                const selectAll = document.getElementById('select-all');
                const checks = document.querySelectorAll('.row-check');
                const bulkBtn = document.getElementById('btn-bulk-restore');

                function refreshState() {
                    const total = checks.length;
                    const checked = document.querySelectorAll('.row-check:checked').length;
                    bulkBtn.disabled = checked === 0;
                    if (total === 0) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                    } else if (checked === 0) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                    } else if (checked === total) {
                        selectAll.checked = true;
                        selectAll.indeterminate = false;
                    } else {
                        selectAll.checked = false;
                        selectAll.indeterminate = true;
                    }
                }

                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        document.querySelectorAll('.row-check').forEach(cb => {
                            cb.checked = selectAll.checked;
                        });
                        refreshState();
                    });
                }

                document.addEventListener('change', function(e) {
                    if (e.target.classList && e.target.classList.contains('row-check')) {
                        refreshState();
                    }
                });

                // init
                refreshState();
            })();
        </script>
    @endpush
@endsection
