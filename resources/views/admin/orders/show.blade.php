@extends('admin.layouts.app')

@section('content')
<style>
    .order-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    h1, h4 {
        border-bottom: 2px solid #eee;
        margin-bottom: 20px;
        padding-bottom: 6px;
        color: #333;
    }

    .breadcrumbs {
        font-size: 0.95rem;
        margin-bottom: 30px;
        background-color: #f1f3f5;
        padding: 10px 20px;
        border-radius: 8px;
    }

    .badge.bg-info,
    .badge.bg-success {
        color: #212529 !important;
        background-color: #e0f0ff !important;
        border: 1px solid #cce5ff;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .form-select {
        max-width: 280px;
    }

    @media (max-width: 768px) {
        .order-container {
            padding: 20px;
        }

        h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="order-container">
    <h1>Chi tiết đơn hàng: {{ $order->code }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mb-3">← Về danh sách đơn hàng</a>

    <div class="breadcrumbs">
        <span><a href="#">Trang chủ</a> / <a href="#">Đơn hàng</a> / Chi tiết đơn hàng</span>
    </div>

    <div class="mb-4">
        <p><strong>Khách hàng:</strong> {{ $order->fullname }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at }}</p>
        <p><strong>Ghi chú:</strong> {{ $order->notes ?? 'Không có' }}</p>
        <p><strong>Phương thức thanh toán:</strong> 
            <span class="badge bg-info">
                @switch($order->payment_id)
                    @case(2) COD @break
                    @case(3) Thanh toán MOMO @break
                    @case(4) Thanh toán VNPAY @break
                    @default Chưa xác định
                @endswitch
            </span>
        </p>
        <p><strong>Trạng thái đơn hàng:</strong> 
            <span class="badge bg-success">{{ $order->currentStatus?->orderStatus?->name ?? 'Chờ Thanh Toán' }}</span>
        </p>
    </div>

    @php
        $currentStatusId = $order->currentStatus?->orderStatus?->id ?? null;
        $finalStatusIds = [6, 7, 8];
        $isFinal = in_array($currentStatusId, $finalStatusIds);
    @endphp

    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
        @csrf
        <div class="input-group mb-3" style="max-width: 320px;">
            <select name="order_status_id" class="form-select" required {{ $isFinal ? 'disabled' : '' }}>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}"
                        {{ $currentStatusId == $status->id ? 'selected' : '' }}
                        @if(!in_array($status->id, [$nextStatusId, 6, 7, 8])) disabled @endif
                    >
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary ms-2" {{ $isFinal ? 'disabled' : '' }}>Cập nhật</button>
        </div>
        @if($errors->has('order_status_id'))
            <div class="alert alert-danger">{{ $errors->first('order_status_id') }}</div>
        @endif
    </form>

    <h4>Danh sách sản phẩm</h4>
<div class="table-responsive mb-4">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Sản phẩm</th>
                <th>Mã SKU</th>
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
                                        @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60">
                                        @else
                                        <span>Không có ảnh</span>
                                        @endif
                                    </td>
                <td>
                    {{ $item->name }}
                    @if($product)
                        <div class="small text-muted">
                            Thương hiệu: {{ $product->brand->name ?? 'N/A' }}
                        </div>
                    @endif
                </td>
                <td>{{ $product->sku ?? 'N/A' }}</td>
                <td>{{ $product ? number_format($product->price, 0, ',', '.') . ' đ' : 'N/A' }}</td>
                <td>{{ number_format($item->variant->price, 0, ',', '.') }} đ</td>
                <td>{{ $item->quantity }}</td>
                <td>
                    {{ number_format(($item->variant ? $item->variant->price : $item->product->price) * $item->quantity, 0, ',', '.') }} đ
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5"></th>
                <th>Tổng cộng = Tiền + Phí Ship :</th>
                <th>  {{ number_format($order->total_amount, 0, ',', '.') }} đ</th>
            </tr>
        </tfoot>
    </table>
</div>
    

    <h4>Lịch sử trạng thái đơn hàng</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Admin\OrderOrderStatus::where('order_id', $order->id)->orderBy('created_at')->get() as $history)
                <tr>
                    <td>{{ $history->orderStatus->name ?? '' }}</td>
                    <td>{{ $history->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
