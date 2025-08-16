@extends('admin.layouts.app')

@section('title', 'Danh sách đơn hàng đã hủy')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Đơn hàng đã hủy</h4>
            <h6>Quản lý các đơn hàng đã bị hủy</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <form method="GET" class="row gx-2 gy-3 align-items-center">
                            <div class="col-auto">
                                <select name="per_page" class="form-select" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 bản ghi</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 bản ghi</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 bản ghi</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 bản ghi</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm mã đơn hàng, tên khách hàng..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if (request('search'))
                                <a href="{{ route('admin.orders.cancelled') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>PT Thanh toán</th>
                            <th>Lý do hủy</th>
                            <th>Ngày hủy</th>
                            <th>Hoàn tiền</th>
                            <th width="120" class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->code }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                            <td>
                                @switch($order->payment_id)
                                    @case(2) COD @break
                                    @case(3) Ví Momo @break
                                    @case(4) Ví VNPay @break
                                    @default Không xác định
                                @endswitch
                            </td>
                            <td>{{ $order->currentStatus->cancel_reason }}</td>
                            <td>{{ $order->currentStatus->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if(in_array($order->payment_id, [3, 4]))
                                    @if($order->check_refund_cancel == 1)
                                        <span class="badge bg-success">Đã hoàn tiền</span>
                                    @else
                                        <span class="badge bg-warning">Chờ hoàn tiền</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Không áp dụng</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    
                                    @if(in_array($order->payment_id, [3, 4]))
                                        <a href="{{ route('admin.orders.confirm-refund', $order->id) }}" class="btn btn-sm btn-primary" title="Xác nhận hoàn tiền">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Không có đơn hàng nào đã hủy</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Hiển thị <b>{{ $orders->firstItem() }}</b> đến <b>{{ $orders->lastItem() }}</b> trong tổng số <b>{{ $orders->total() }}</b> bản ghi
                </div>
                <div class="pagination-wrap">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: #7367f0;
        border-color: #7367f0;
    }
    .page-link {
        color: #7367f0;
    }
    .badge {
        font-size: 0.85em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .table th {
        white-space: nowrap;
    }
</style>
@endpush