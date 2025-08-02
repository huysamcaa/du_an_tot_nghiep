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
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex gap-2">
                        <select name="year" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                        <select name="month" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                            @foreach ($months as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                            @endforeach
                        </select>
                        <select name="view" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                            <option value="month" {{ $view == 'month' ? 'selected' : '' }}>Theo tháng</option>
                            <option value="week" {{ $view == 'week' ? 'selected' : '' }}>Theo tuần</option>
                            <option value="day" {{ $view == 'day' ? 'selected' : '' }}>Theo ngày</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="150"></canvas>
                </div>
            </div>

            <!-- 2 card nhỏ cho sản phẩm -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header"><h5>Sản phẩm mua nhiều nhất</h5></div>
                        <div class="card-body">
                            <ul>
                                @forelse($topProductsBySales as $item)
                                    <li>{{ $item->name ?? 'N/A' }} - {{ $item->total }} sản phẩm</li>
                                @empty
                                    <li>Chưa có dữ liệu</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header"><h5>Sản phẩm được yêu thích nhất</h5></div>
                        <div class="card-body">
                            <ul>
                                @forelse($topProductsByFavorites as $item)
                                    <li>{{ $item->name ?? 'N/A' }} - {{ $item->total }} lượt yêu thích</li>
                                @empty
                                    <li>Chưa có dữ liệu</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bên phải -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><h5>Trạng thái đơn hàng</h5></div>
                <div class="card-body">
                    <canvas id="orderStatusChart" height="200"></canvas>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5>Top khách hàng</h5></div>
                <div class="card-body">
                    <ul>
                        @forelse($topCustomers as $customer)
                            <li>{{ $customer->user->name ?? 'N/A' }} -
                                {{ number_format($customer->total, 0, ',', '.') }} đ</li>
                        @empty
                            <li>Chưa có dữ liệu</li>
                        @endforelse
                    </ul>
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
                                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF'],
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
