    @extends('admin.layouts.app')

    @section('content')
        <div class="content">
            <div class="animated fadeIn">
                <!-- 4 box nhỏ thống kê -->
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-1">
                                        <i class="pe-7s-cash"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span
                                                    class="revenue-value">{{ number_format($revenue, 0, ',', '.') }}
                                                    đ</span></div>
                                            <div class="stat-heading">Doanh thu</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-2">
                                        <i class="pe-7s-cart"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count">{{ $orderCount }}</span></div>
                                            <div class="stat-heading">Số đơn hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-3">
                                        <i class="pe-7s-browser"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count">{{ $productCount }}</span></div>
                                            <div class="stat-heading">Số sản phẩm</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-4">
                                        <i class="pe-7s-users"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count">{{ $userCount }}</span></div>
                                            <div class="stat-heading">Số khách hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <!-- Bên trái -->
                    <div class="col-lg-8">
                        <!-- Biểu đồ doanh thu -->
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Thống kê doanh thu</h4>
                                <form method="GET" action="{{ route('admin.dashboard') }}"
                                    class="row g-2 align-items-end">
                                    <div class="col-auto">
                                        <label for="from_date">Từ ngày</label>
                                        <input type="date" name="from_date" id="from_date"
                                            value="{{ request('from_date') }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-auto">
                                        <label for="to_date">Đến ngày</label>
                                        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary btn-sm">Xem</button>
                                    </div>
                                </form>

                            </div>
                            <div class="card-body">
                                <canvas id="revenueChart" height="150"></canvas>
                            </div>
                        </div>
                        <!-- Orders -->
                        <div class="card-body">
                            <h4 class="box-title">Orders </h4>
                        </div>
                        <div class="card-body--">
                            <div class="table-stats order-table ov-h">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th class="serial">#</th>
                                            <th class="avatar">Avatar</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topCustomers as $index => $order)
                                            <tr>
                                                <td class="serial">{{ $index + 1 }}.</td>
                                                <td class="avatar">
                                                    <div class="round-img">
                                                        <a href="#">
                                                            <img class="rounded-circle"
                                                                src="{{ asset($order->user->avatar ?? 'images/avatar/default.jpg') }}"
                                                                alt="" style="width:40px; height:40px;">
                                                        </a>
                                                    </div>
                                                </td>
                                               {{-- <td>#{{ $order->user->id }}</td> 
                                                <td><span class="name">{{ $order->user->name }}</span></td>
                                                <td><span
                                                        class="product">{{ number_format($order->total_amount, 0, ',', '.') }}
                                                        đ</span></td>
                                                <td><span class="count">{{ $order->total_orders }}</span></td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- /.table-stats -->
                        </div>
                    </div>
                    <!-- Bên phải -->
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Trạng thái đơn hàng</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="orderStatusChart" height="200"></canvas>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Sản phẩm mua nhiều nhất</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Số lượng bán</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topProductsBySales as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->total }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">Chưa có dữ liệu</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Sản phẩm được yêu thích nhất</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Lượt yêu thích</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topProductsByFavorites as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->total }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">Chưa có dữ liệu</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @push('scripts')
            @php
                // Doanh thu (theo tháng hoặc theo tuần, tùy $view)
                $periodLabels = $revenueByPeriod->pluck('label');
                $periodData = $revenueByPeriod->pluck('total');

                // Trạng thái đơn hàng
                $statusLabels = $orderStatusStats->pluck('name');
                $statusData = $orderStatusStats->pluck('total');
            @endphp

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Biểu đồ doanh thu theo tháng
                    new Chart(document.getElementById('revenueChart').getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json($periodLabels),
                            datasets: [{
                                label: 'Doanh thu (VND)',
                                data: @json($periodData),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: '#4bc0c0',
                                fill: true,
                                borderWidth: 2,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString('vi-VN') + ' đ';
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Biểu đồ trạng thái đơn hàng
                    new Chart(document.getElementById('orderStatusChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($statusLabels),
                            datasets: [{
                                data: @json($statusData),
                                backgroundColor: ['#36A2EB','#FF9F40', '#FFCE56', '#4BC0C0', '#9966FF', '#FF6384','#C9CBCF'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                });
            </script>
        @endpush
    @endsection
