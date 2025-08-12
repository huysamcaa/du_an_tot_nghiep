@extends('admin.layouts.app')

@section('title', 'Danh sách đơn hàng COD')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Đơn hàng COD</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="active">Đơn hàng COD</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="col-md-12">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.orders.cancelled') }}" class="btn btn-success">
                <i class="fa fa-trash"></i> Đơn Hàng Đã Hủy
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <strong class="card-title">Danh sách đơn hàng COD</strong>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- Lọc số lượng hiển thị --}}
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex align-items-center" style="gap: 12px;">
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

                    {{-- Tìm kiếm --}}
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="w-50">
                        <div class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Tìm mã đơn hàng, tên khách hàng..." value="{{ request('search') }}">
                            <button class="btn btn-primary ml-1" type="submit">Tìm</button>
                            @if (request('search'))
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary ml-1">Xóa</a>
                            @endif
                        </div>
                    </form>
                </div>

                <table id="orders-table" class="table table-striped table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th style="width: 5%;">STT</th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th style="width: 8%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                <td>{{ $order->code }}</td>
                                <td>{{ $order->fullname }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $order->currentStatus?->orderStatus?->name ?? 'Lỗi Thanh Toán' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Hiển thị từ {{ $orders->firstItem() ?? 0 }} đến {{ $orders->lastItem() ?? 0 }} trên
                        tổng số {{ $orders->total() }} đơn hàng
                    </div>
                    <div>
                        {!! $orders->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-4') !!}
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
            $('#orders-table').DataTable({
                "order": [[ 3, "desc" ]],
                "paging": false,
                "searching": false,
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [5] }
                ],
                "language": {
                    "emptyTable": "Không có đơn hàng nào trong bảng",
                    "zeroRecords": "Không tìm thấy đơn hàng nào phù hợp"
                }
            });
        });
    </script>
@endsection
