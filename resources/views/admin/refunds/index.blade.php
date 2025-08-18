@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Danh sách yêu cầu Hoàn tiền</h4>
            <h6>Xem / Tìm kiếm / Lọc yêu cầu hoàn tiền</h6>
        </div>
    </div>

    {{-- Thông báo thành công --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">

            {{-- Form tìm kiếm & lọc --}}
            {{-- Sử dụng flexbox để căn chỉnh các phần tử trên cùng một hàng --}}
            <form method="GET" action="{{ route('admin.refunds.index') }}" class="row g-2 align-items-center mb-3">
                {{-- Tìm kiếm --}}
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control"
                        placeholder="Tìm mã đơn hàng, tên khách hàng..."
                        value="{{ request('search') }}">
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-2">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Thời gian --}}
                <div class="col-md-2">
                    <select name="date_range" class="form-control" onchange="this.form.submit()">
                        <option value="newest_first" {{ request('date_range') == 'newest_first' ? 'selected' : '' }}>
                            Mới thêm gần đây
                        </option>
                        <option value="7_days_ago" {{ request('date_range') == '7_days_ago' ? 'selected' : '' }}>
                            7 ngày qua
                        </option>
                        <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>
                            Tháng trước
                        </option>
                        <option value="oldest_first" {{ request('date_range') == 'oldest_first' ? 'selected' : '' }}>
                            Cũ nhất
                        </option>
                    </select>
                </div>

                {{-- Số lượng hiển thị --}}
                <div class="col-md-1">
                    <select name="perPage" class="form-control" onchange="this.form.submit()">
                        @foreach([10,25,50,100] as $size)
                        <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nút tìm kiếm & xóa lọc --}}
                {{-- Thay đổi col-md-2 thành col-md-3 và sử dụng flex để tránh xuống dòng --}}
                <div class="col-md-4 d-flex" style="gap: 6px;">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    @if (request()->hasAny(['search', 'status', 'date_range', 'perPage']))
                    <a href="{{ route('admin.refunds.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                    @endif
                </div>
            </form>

            {{-- Bảng yêu cầu hoàn tiền --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">STT</th>
                            <th>Khách hàng</th>
                            <th style="width: 12%;">Đơn hàng</th>
                            <th style="width: 15%;">Tổng tiền</th>
                            <th style="width: 10%;">Trạng thái</th>
                            <th style="width: 10%;">Tài khoản</th>
                            <th style="width: 15%;">Ngày tạo</th>
                            <th style="width: 8%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($refunds as $refund)
                        <tr>
                            <td>{{ $loop->iteration + ($refunds->currentPage() - 1) * $refunds->perPage() }}</td>
                            <td class="text-start">
                                <div class="d-flex align-items-center" style="gap: 10px;">
                                    <img src="{{ $refund->user->avatar ? asset('storage/' . $refund->user->avatar) : asset('assets/admin/img/profile.svg') }}"
                                        alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                                    <span>{{ $refund->user->name ?? 'Không rõ' }}</span>
                                </div>
                            </td>
                            <td>{{ $refund->order->code ?? 'Không rõ' }}</td>
                            <td class="text-end">{{ number_format($refund->total_amount, 0, ',', '.') }}₫</td>
                            <td>
                                @switch($refund->status)
                                @case('pending') <span class="badge bg-warning">Chờ xử lý</span> @break
                                @case('receiving') <span class="badge bg-info">Đang tiếp nhận</span> @break
                                @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                                @case('rejected') <span class="badge bg-danger">Đã từ chối</span> @break
                                @case('failed') <span class="badge bg-secondary">Thất bại</span> @break
                                @case('cancel') <span class="badge bg-dark">Đã hủy</span> @break
                                @default <span class="badge bg-secondary">Không rõ</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($refund->bank_account_status)
                                @case('unverified') <span class="badge bg-warning">Chưa xác minh</span> @break
                                @case('verified') <span class="badge bg-success">Đã xác minh</span> @break
                                @case('sent') <span class="badge bg-info">Đã gửi</span> @break
                                @default <span class="badge bg-secondary">Không rõ</span>
                                @endswitch
                            </td>
                            <td>{{ $refund->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center" style="gap: 10px;">
                                    <a href="{{ route('admin.refunds.show', $refund) }}" title="Xem chi tiết">
                                        <img src="{{ asset('assets/admin/img/icons/eye.svg') }}" alt="Xem">
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Không có yêu cầu hoàn tiền nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Hiển thị từ {{ $refunds->firstItem() ?? 0 }} đến {{ $refunds->lastItem() ?? 0 }} trên tổng số {{ $refunds->total() }} yêu cầu
                </div>
                <div>
                    {!! $refunds->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
