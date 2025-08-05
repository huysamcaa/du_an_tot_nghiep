@extends('admin.layouts.app')

@section('content')

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Yêu cầu Hoàn tiền</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                                <li class="active">Yêu cầu Hoàn tiền</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Danh sách đơn hoàn</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form method="GET" action="{{ route('admin.refunds.index') }}" class="d-flex align-items-center" style="gap: 12px;">
                            <div>
                                <label for="perPage" style="font-weight:600;">Hiển thị:</label>
                                <select name="perPage" id="perPage" class="form-control d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                    <option value="10" {{ request('perPage') == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('perPage') == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == '50' ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage') == '100' ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </form>

                        <form method="GET" action="{{ route('admin.refunds.index') }}" class="w-50">
                            <div class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Tìm mã đơn hàng, tên khách hàng..." value="{{ request('search') }}">
                                <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                                @if (request('search'))
                                    <a href="{{ route('admin.refunds.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <table id="refund-table" class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th>Khách hàng</th>
                                <th style="width: 12%;">Đơn hàng</th>
                                <th style="width: 15%;">Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Tài khoản</th>
                                <th>Hoàn tiền</th>
                                <th style="width: 15%;">Ngày tạo</th>
                                <th style="width: 8%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($refunds as $refund)
                                <tr>
                                    <td>{{ $refund->id }}</td>
                                    <td>{{ $refund->user->name ?? 'Không rõ' }}</td>
                                
                                    <td>{{ $refund->order->code ?? 'Không rõ'}} </td>
                                    <td class="text-right">{{ number_format($refund->total_amount, 0, ',', '.') }}₫</td>
                                    <td>
                                        @switch($refund->status)
                                            @case('pending') <span class="badge badge-warning">Chờ xử lý</span> @break
                                            @case('receiving') <span class="badge badge-info">Đang tiếp nhận</span> @break
                                            @case('completed') <span class="badge badge-success">Hoàn thành</span> @break
                                            @case('rejected') <span class="badge badge-danger">Đã từ chối</span> @break
                                            @case('failed') <span class="badge badge-secondary">Thất bại</span> @break
                                            @case('cancel') <span class="badge badge-dark">Đã hủy</span> @break
                                            @default {{ $refund->status }}
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($refund->bank_account_status)
                                            @case('unverified') <span class="badge badge-warning">Chưa xác minh</span> @break
                                            @case('verified') <span class="badge badge-success">Đã xác minh</span> @break
                                            @case('sent') <span class="badge badge-info">Đã gửi</span> @break
                                            @default <span class="badge badge-secondary">Không rõ</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($refund->is_send_money)
                                            <span class="badge badge-success">Đã</span>
                                        @else
                                            <span class="badge badge-danger">Chưa</span>
                                        @endif
                                    </td>
                                    <td>{{ $refund->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.refunds.show', $refund) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Không có yêu cầu hoàn tiền nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị từ {{ $refunds->firstItem() ?? 0 }} đến {{ $refunds->lastItem() ?? 0 }} trên
                            tổng số {{ $refunds->total() }} yêu cầu
                        </div>
                        <div>
                            {!! $refunds->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    {{-- jQuery and DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#refund-table').DataTable({
                "order": [[ 7, "desc" ]],
                "paging": false,
                "searching": false,
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [8] } // Vô hiệu hóa sắp xếp cho các cột Trạng thái, Tài khoản, Hoàn tiền, Hành động
                ],
                "language": {
                    "emptyTable": "Không có yêu cầu hoàn tiền nào trong bảng",
                    "zeroRecords": "Không tìm thấy yêu cầu hoàn tiền nào phù hợp"
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
