@extends('admin.layouts.app')

@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Admin</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Trang chủ</a></li>
                            <li><a href="#">Khuyến mãi</a></li>
                            <li class="active">Danh sách mã giảm giá</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="col-md-12">
        {{-- Nút thêm và Mã đã xóa --}}
        <div class="mb-3 d-flex" style="gap: 10px;">
            <a href="{{ route('admin.coupon.create') }}" class="btn btn-success" title="Thêm mã giảm giá">
                <i class="fa fa-plus"></i> Thêm mã
            </a>
            <a href="{{ route('admin.coupon.trashed') }}" class="btn btn-secondary" title="Mã đã xóa">
                <i class="fa fa-trash"></i> Mã đã xóa
            </a>
        </div>
        <div class="card">

            <div class="card-header">
                <strong class="card-title">Danh sách mã giảm giá</strong>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.coupon.index') }}" class="mb-3 d-flex"
                    style="gap: 12px; align-items: center;">
                    <div>
                        <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                        <select name="perPage" id="perPage" class="form-control d-inline-block"
                            style="width:auto;" onchange="this.form.submit()">
                            <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10
                            </option>
                            <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25
                            </option>
                            <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50
                            </option>
                            <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100
                            </option>
                        </select>
                    </div>
                </form>


                <form method="GET" action="{{ route('admin.coupon.index') }}" class="mb-3 w-100">
                    <div class="input-group w-100">
                        <input type="text" name="search" class="form-control"
                            placeholder="Tìm mã hoặc tiêu đề..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Tìm</button>
                        @if (request('search'))
                        <a href="{{ route('admin.coupon.index') }}"
                            class="btn btn-outline-secondary">Xóa</a>
                        @endif
                    </div>
                </form>



                <table id="coupon-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
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
                        @foreach ($coupons as $coupon)
                        <tr>
                            <td>{{ $loop->iteration + ($coupons->currentPage() - 1) * $coupons->perPage() }}
                            </td>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->title }}</td>
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
                                <span
                                    class="badge badge-{{ $coupon->is_expired ? 'warning' : 'secondary' }}">
                                    {{ $coupon->is_expired ? 'Có hạn' : 'Vô hạn' }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                    {{ $coupon->is_active ? 'Bật' : 'Tắt' }}
                                </span>
                            </td>
                            <td>{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y H:i') : '--' }}
                            </td>
                            <td>{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y H:i') : '--' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.coupon.show', $coupon->id) }}"
                                    class="btn btn-sm btn-outline-info" title="Xem">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}"
                                    class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupon.destroy', $coupon->id) }}"
                                    method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Xác nhận xóa mã này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Xóa">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Hiển thị từ {{ $coupons->firstItem() ?? 0 }} đến {{ $coupons->lastItem() ?? 0 }} trên
                        tổng số {{ $coupons->total() }} mã
                    </div>

                    <div>
                        {!! $coupons->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#coupon-table').DataTable({
            order: [
                [0, 'asc']
            ],
            paging: false,
            searching: false,
            info: false
        });

        // Confirm xóa
        $(document).on('click', '.btn-danger', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa mã này?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection

<style>
    .pagination {
        display: flex !important;
    }
</style>
