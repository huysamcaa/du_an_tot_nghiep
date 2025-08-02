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
                            <li><a href="#">Trang chủ</a></li>
                            <li class="active">Yêu cầu Hoàn tiền</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong class="card-title">Danh sách yêu cầu hoàn tiền</strong>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <div class="mb-3 d-flex justify-content-between">
                            <form method="GET" action="{{ route('admin.refunds.index') }}" class="d-flex" style="gap: 12px; align-items: center;">
                                <div>
                                    <label for="per_page" style="font-weight:600;">Hiển thị:</label>
                                    <select name="per_page" id="per_page" class="form-control d-inline-block" style="width:auto;" onchange="this.form.submit()">
                                        @foreach([10, 25, 50, 100] as $size)
                                        <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            <form method="GET" action="{{ route('admin.refunds.index') }}" style="max-width:350px;">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm mã hoặc tên khách..." value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </form>
                        </div>

                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#R</th>
                                    <th>Khách hàng</th>
                                    <th>Đơn hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>TK ngân hàng</th>
                                    <th>Hoàn tiền</th>
                                    <th>Ngày yêu cầu</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($refunds as $refund)
                                <tr>
                                    <td>R{{ $refund->id }}</td>
                                    <td>{{ $refund->user->name }}</td>
                                    <td>{{ $refund->order->code }}</td>
                                    <td>{{ number_format($refund->total_amount, 0, ',', '.') }}₫</td>
                                    <td>
                                        @switch($refund->status)
                                        @case('pending') Chờ xử lý @break
                                        @case('receiving') Đang tiếp nhận @break
                                        @case('completed') Hoàn thành @break
                                        @case('rejected') Đã từ chối @break
                                        @case('failed') Thất bại @break
                                        @case('cancel') Đã hủy @break
                                        @default {{ $refund->status }}
                                        @endswitch
                                    </td>
                                    <td>{{ ucfirst($refund->bank_account_status) }}</td>
                                    <td>
                                        @if($refund->is_send_money)
                                        <span class="badge badge-success">Đã</span>
                                        @else
                                        <span class="badge badge-danger">Chưa</span>
                                        @endif
                                    </td>
                                    <td>{{ $refund->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.refunds.show', $refund) }}" class="btn btn-sm btn-info">Xem</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $refunds->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bootstrap-data-table').DataTable({
            order: [
                [0, 'desc']
            ],
            paging: false,
            info: false,
            searching: false
        });
    });
</script>
@endpush
@endsection
