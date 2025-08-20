@extends('admin.layouts.app')

@section('title', 'Danh sách đơn hàng COD')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Đơn hàng COD</h4>
            <h6>Quản lý các đơn hàng thanh toán khi nhận hàng</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.orders.cancelled') }}" class="btn btn-primary">
                <i class="fas fa-trash me-2"></i> Đơn Hàng Đã Hủy
            </a>
        </div>
    </div>
    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="mb-3 fw-bold">Bộ Lọc Đơn Hàng</h6>
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="row g-3">
                    <!-- 1. Theo trạng thái đơn hàng -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Đang giao</option>
                            <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="7" {{ request('status') == '7' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>Hoàn hàng</option>
                        </select>
                    </div>

                    <!-- 2. Theo thời gian -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Từ ngày</label>
                        <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Đến ngày</label>
                        <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <!-- 3. Theo khách hàng -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Tên KH/SĐT</label>
                        <input type="text" class="form-control" name="customer" placeholder="Tên hoặc SĐT" value="{{ request('customer') }}">
                    </div>

                    <!-- 4. Theo mã đơn hàng -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Mã đơn hàng</label>
                        <input type="text" class="form-control" name="order_code" placeholder="Nhập mã đơn" value="{{ request('order_code') }}">
                    </div>

                    <!-- 5. Theo phương thức thanh toán -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Thanh toán</label>
                        <select class="form-select" name="payment_method">
                            <option value="">Tất cả</option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>COD</option>
                            <option value="wallet" {{ request('payment_method') == 'wallet' ? 'selected' : '' }}>Ví điện tử Momo</option>
                            <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Ví điện tử VnPay</option>
                        </select>
                    </div>

                    <!-- 6. Theo tổng giá trị đơn hàng -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Giá từ (đ)</label>
                        <input type="number" class="form-control" name="min_amount" placeholder="Tối thiểu" value="{{ request('min_amount') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Giá đến (đ)</label>
                        <input type="number" class="form-control" name="max_amount" placeholder="Tối đa" value="{{ request('max_amount') }}">
                    </div>

                    <!-- 7. Theo khu vực giao hàng -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Tỉnh/Thành phố</label>
                        <input type="text" class="form-control" name="city" placeholder="Nhập tỉnh/thành" value="{{ request('city') }}">
                    </div>

                    <!-- 8. Theo sản phẩm trong đơn -->
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Sản phẩm</label>
                        <input type="text" class="form-control" name="product" placeholder="Tên hoặc mã SP" value="{{ request('product') }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i>Tìm kiếm</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
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
                                <select name="perPage" class="form-select" onchange="this.form.submit()">
                                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10 bản ghi</option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25 bản ghi</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50 bản ghi</option>
                                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100 bản ghi</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="col-md-6">
                        <form method="GET" class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm mã đơn hàng, tên khách hàng..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if (request('search'))
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            @endif
                        </form>
                    </div> --}}
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">STT</th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Giá Tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th width="10%" class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                <td>{{ $order->code }}</td>
                                <td class="customer-info" data-bs-toggle="collapse" href="#order-details-{{ $order->id }}">
                                    <span class="d-block">{{ $order->fullname }}</span>
                                    <i class="fas fa-chevron-down toggle-icon ms-2"></i>
                                </td>
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                <td>
                                    @if($order->payment_id == '2')
                                        <span class="badge bg-success">COD</span>
                                    @elseif($order->payment_id == '3')
                                        <span class="badge bg-info">Ví điện tử Momo</span>
                                    @elseif($order->payment_id == '4')
                                        <span class="badge bg-primary">Ví điện tử VnPay</span>
                                    @else
                                        <span class="badge bg-secondary">Khác</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'processing' => 'primary',
                                            'shipping' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ][$order->currentStatus?->orderStatus?->slug ?? ''] ?? 'info';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $order->currentStatus?->orderStatus?->name ?? 'Lỗi Thanh Toán' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn" title="Xem chi tiết">
                                        <img src="{{ asset('assets/admin/img/icons/eye.svg') }}" alt="xem">
                                    </a>
                                </td>
                            </tr>
                            <tr class="collapse" id="order-details-{{ $order->id }}">
                                <td colspan="8" class="order-details-cell">
                                    <div class="p-3 bg-light rounded shadow-sm">
                                        <h6 class="fw-bold mb-2">Thông tin chi tiết</h6>
                                        <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                                        <p class="mb-1"><strong>SĐT:</strong> {{ $order->phone_number }}</p>
                                        <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                                        <p><p>
                                        @if($order->note)
                                        <p class="mb-1"><strong>Ghi chú:</strong> {{ $order->note }}</p>
                                        @endif
                                        {{-- <h6 class="fw-bold mt-3 mb-2">Sản phẩm:</h6>
                                        @if($order->orderItems?->isNotEmpty())
                                            <ul class="list-unstyled mb-0">
                                                @foreach($order->orderItems as $item)
                                                    <li>- {{ $item->product_name }} ({{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }} đ)</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted">Không có sản phẩm nào trong đơn hàng này.</p>
                                        @endif --}}
                                         
                                        @foreach ($order->items as $item)
                                @php
                                    $product = \App\Models\Admin\Product::find($item->product_id);
                                @endphp
                                <tr></tr>
                                <tr>
                                    
                                    <td>
                                        @if ($product && $product->thumbnail)
                                            <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <div class="small text-muted">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                        @if ($product)
                                            <div class="small text-muted">Thương hiệu: {{ $product->brand->name ?? 'N/A' }}</div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->variant->price, 0, ',', '.') }} đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-bold">
                                        {{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }} đ
                                    </td>
                                </tr>
                                @endforeach
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Không có đơn hàng nào</td>
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
    .customer-info {
        cursor: pointer;
        user-select: none;
    }
    .toggle-icon {
        transition: transform 0.2s ease-in-out;
        font-size: 0.8em;
    }
    .customer-info[aria-expanded="true"] .toggle-icon {
        transform: rotate(180deg);
    }
    .order-details-cell {
        padding: 0 !important;
        border-top: none;
    }
    .order-details-cell > div {
        border-top-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
    }
    .table > :not(caption)>*>* {
        padding: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const collapseElements = document.querySelectorAll('.collapse');

        collapseElements.forEach(function(el) {
            el.addEventListener('shown.bs.collapse', function() {
                // Find the icon and rotate it when the element is shown
                const icon = document.querySelector(`[href="#${el.id}"] .toggle-icon`);
                if (icon) {
                    icon.style.transform = 'rotate(180deg)';
                }
            });

            el.addEventListener('hidden.bs.collapse', function() {
                // Find the icon and reset its rotation when the element is hidden
                const icon = document.querySelector(`[href="#${el.id}"] .toggle-icon`);
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
</script>
@endpush