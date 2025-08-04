@extends('admin.layouts.app')

@section('content')

{{-- Breadcrumbs --}}
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Chi tiết đơn hàng</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                            <li class="active">Chi tiết</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Nội dung chính --}}
<div class="content">
    <div class="animated fadeIn">

        {{-- Thông tin đơn hàng --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thông tin đơn hàng: {{ $order->code }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Khách hàng:</strong> {{ $order->fullname }}</div>
                    <div class="col-md-4"><strong>SĐT:</strong> {{ $order->phone_number }}</div>
                    <div class="col-md-4"><strong>Ngày đặt:</strong> {{ $order->created_at }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6"><strong>Địa chỉ:</strong> {{ $order->address }}</div>
                    <div class="col-md-6"><strong>Ghi chú:</strong> {{ $order->notes ?? 'Không có' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Phương thức thanh toán:</strong>
                        <span class="badge bg-info">
                            @switch($order->payment_id)
                                @case(2) COD @break
                                @case(3) MOMO @break
                                @case(4) VNPAY @break
                                @default Chưa xác định
                            @endswitch
                        </span>
                    </div>
                    <div class="col-md-4"><strong>Phí vận chuyển:</strong> 30.000 đ</div>
                    <div class="col-md-4"><strong>Trạng thái thanh toán:</strong>
                        <span class="badge bg-info">
                            @switch($order->is_paid)
                                @case(1) Đã thanh toán @break
                                @case(0) Chưa thanh toán @break
                                @default Chưa xác định
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"><strong>Trạng thái đơn hàng:</strong>
                        <span class="badge bg-success">{{ $order->currentStatus?->orderStatus?->name ?? 'Chờ thanh toán' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cập nhật trạng thái --}}
        @php
            $currentStatusId = $order->currentStatus?->orderStatus?->id ?? null;
            $finalStatusIds = [6, 7, 8];
            $isFinal = in_array($currentStatusId, $finalStatusIds);
        @endphp
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Cập nhật trạng thái đơn hàng</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="form-inline">
                    @csrf
                    <div class="input-group" style="max-width: 320px;">
                        <select name="order_status_id" class="form-select" required {{ $isFinal ? 'disabled' : '' }}>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}"
                                    {{ $currentStatusId == $status->id ? 'selected' : '' }}
                                    @if(
                                        !in_array($status->id, [$nextStatusId, 6, 7]) ||
                                        ($status->id == 7 && $currentStatusId != 5) ||
                                        (in_array($status->id, [6,8]) && $currentStatusId == 5)
                                    ) disabled @endif
                                >{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary ms-2" {{ $isFinal ? 'disabled' : '' }}>Cập nhật</button>
                    </div>
                    @if($errors->has('order_status_id'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('order_status_id') }}</div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Danh sách sản phẩm</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th>SKU</th>
                                <th>Giá gốc</th>
                                <th>Giá bán</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            @php
                                $product = \App\Models\Admin\Product::find($item->product_id);
                            @endphp
                            <tr>
                                <td>
                                    @if($product && $product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60">
                                    @else
                                        <span>Không có ảnh</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->name }}
                                    @if($product)
                                        <div class="small text-muted">Thương hiệu: {{ $product->brand->name ?? 'N/A' }}</div>
                                    @endif
                                </td>
                                <td>{{ $product->sku ?? 'N/A' }}</td>
                                <td>{{ number_format($product->price ?? 0, 0, ',', '.') }} đ</td>
                                <td>{{ number_format($item->variant->price, 0, ',', '.') }} đ</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format(($item->variant ? $item->variant->price : $product->price) * $item->quantity, 0, ',', '.') }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-end">Tổng cộng (gồm phí ship):</th>
                                <th>{{ number_format($order->total_amount, 0, ',', '.') }} đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Lịch sử trạng thái --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Lịch sử trạng thái</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead>
                            <tr>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Admin\OrderOrderStatus::where('order_id', $order->id)->orderBy('created_at')->get() as $history)
                            <tr>
                                <td>{{ $history->orderStatus->name ?? '—' }}</td>
                                <td>{{ $history->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Nút quay lại --}}
        <div class="mt-4">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

    </div>
</div>

@endsection
