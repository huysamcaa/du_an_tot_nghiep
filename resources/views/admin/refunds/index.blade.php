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
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách đơn hoàn</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Khách hàng</th>
                                    <th>Đơn hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Tài khoản ngân hàng</th>
                                    <th>Ngày</th>
                                    <th>Hoàn tiền</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($refunds as $refund)
                                <tr class="text-center align-middle">
                                    <td>R{{ $refund->id }}</td>
                                    <td>{{ $refund->user->name ?? 'Không rõ' }}</td>
                                    <td>{{ $refund->order->code }}</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div><!-- .animated -->
</div>
@endsection
