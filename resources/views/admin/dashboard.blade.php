        @extends('admin.layouts.app')

        @section('content')
            <style>
                #revenue-chart-section {
                    display: none;
                }
            </style>
            <div class="content">
                <div class="animated fadeIn">
                    <!-- 4 box nhỏ thống kê -->
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="dash-widget dash2">
                                <div class="dash-widgetimg">
                                    <span><img src="{{ asset('assets/admin/img/icons/dash2.svg') }}" alt="img"></span>
                                </div>
                                <div class="dash-widgetcontent">
                                    <div class="stat-heading">
                                        <h5>Doanh thu</h5>
                                    </div>
                                    <div class="stat-text"><span
                                            class="revenue-value">{{ number_format($revenue, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="dash-widget dash2">
                                <div class="dash-widgetimg">
                                    <span><img src="{{ asset('assets/admin/img/icons/dash1.svg') }}" alt="img"></span>
                                </div>
                                <div class="dash-widgetcontent">
                                    <div class="stat-heading">
                                        <h5>Đơn hàng</h5>
                                    </div>
                                    <div class="stat-text"><span class="count order-count">{{ $orderCount }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="dash-widget dash2">
                                <div class="dash-widgetimg">
                                    <span><img src="{{ asset('assets/admin/img/icons/product.svg') }}"
                                            alt="img"></span>
                                </div>
                                <div class="dash-widgetcontent">
                                    <div class="stat-heading">
                                        <h5>Sản phẩm</h5>
                                    </div>
                                    <div class="stat-text"><span class="count product-count">{{ $productCount }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="dash-widget dash2">
                                <div class="dash-widgetimg">
                                    <span><img src="{{ asset('assets/admin/img/icons/users1.svg') }}" alt="img"></span>
                                </div>
                                <div class="dash-widgetcontent">
                                    <div class="stat-heading">
                                        <h5>Khách hàng</h5>
                                        <div class="stat-text"><span class="count user-count">{{ $userCount }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4>Tổng doanh thu hôm nay: {{ number_format($revenueToday, 0, ',', '.') }}đ</h4>
                    </div>
                    <div class="row">
                        <!-- Bên trái -->
                        <div class="col-lg-8">
                            <!-- Biểu đồ doanh thu -->
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Thống kê doanh thu</h4>
                                </div>
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <form id="filterForm" class="row g-2 align-items-end">
                                        <div class="col-auto">
                                            <label for="from_date">Từ ngày</label>
                                            <input type="date" name="from_date" id="from_date"
                                                value="{{ request('from_date') }}" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-auto">
                                            <label for="to_date">Đến ngày</label>
                                            <input type="date" name="to_date" id="to_date"
                                                value="{{ request('to_date') }}" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-auto">
                                            <label for="view">Chế độ xem</label>
                                            <select name="view" id="view" class="form-control form-control-sm">
                                                <option value="month" {{ request('view') == 'month' ? 'selected' : '' }}>

                                                    tháng</option>
                                                <option value="week" {{ request('view') == 'week' ? 'selected' : '' }}>

                                                    tuần</option>
                                                <option value="day" {{ request('view') == 'day' ? 'selected' : '' }}>

                                                    ngày</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label for="year">Năm</label>
                                            <select name="year" id="year" class="form-control form-control-sm">
                                                @foreach ($years as $y)
                                                    <option value="{{ $y }}"
                                                        {{ request('year', $year) == $y ? 'selected' : '' }}>
                                                        {{ $y }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-auto">
                                            <label for="month">Tháng</label>
                                            <select name="month" id="month" class="form-control form-control-sm">
                                                @foreach ($months as $m)
                                                    <option value="{{ $m }}"
                                                        {{ request('month', $month) == $m ? 'selected' : '' }}>
                                                        Tháng {{ $m }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-auto">
                                            <button type="submit" class="btn btn-primary btn-sm">Xem</button>
                                        </div> --}}
                                    </form>

                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" height="150"></canvas>
                                </div>
                            </div>
                            <!-- Orders -->
                            <div class="card-body">
                                <h4 class="box-title">Top khách hàng </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-stats order-table ov-h">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                                <th>Stt</th>
                                                <th>Avatar</th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody id="topCustomers">
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
                                                    <td>#{{ $order->user->id }}</td>
                                                    <td><span class="name">{{ $order->user->name }}</span></td>
                                                    <td><span
                                                            class="product">{{ number_format($order->total_amount, 0, ',', '.') }}
                                                            đ</span></td>
                                                    <td><span class="count">{{ $order->total_orders }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> <!-- /.table-stats -->
                            </div>
                            <div class="card-body">
                                <h4 class="box-title">Mã khuyến mãi </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-stats order-table ov-h">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                                <th>Mã khuyến mãi</th>
                                                <th>Giá trị khuyến mãi</th>
                                                <th>Số lần sử dụng</th>
                                                <th>Doanh thu từ mã</th>
                                            </tr>
                                        </thead>
                                        <tbody id="topCoupons">
                                            @forelse($topCoupons as $coupon)
                                                <tr>
                                                    <td>{{ $coupon->code }}</td>
                                                    <td>
                                                        @if ($coupon->discount_type === 'percent')
                                                            {{ $coupon->value }}%
                                                        @else
                                                            {{ number_format($coupon->discount_value, 0, ',', '.') }} đ
                                                        @endif
                                                    </td>
                                                    <td>{{ $coupon->total_uses }}</td>
                                                    <td>{{ number_format($coupon->total_revenue, 0, ',', '.') }} đ</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Chưa có dữ liệu</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
                                    <h5>Phương thức thanh toán</h5>
                                </div>
                                <div class="chart-container"style="width:300px; margin:auto;">
                                    <canvas id="paymentChart"></canvas>
                                    <div id="paymentTotal" style="text-align:center; margin-top:10px; font-weight:bold;">
                                    </div>
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
                                                    <th>Stt</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Ảnh</th>
                                                    <th>Số lượng bán</th>
                                                </tr>
                                            </thead>
                                            <tbody id="topProductsBySales">
                                                @forelse($topProductsBySales as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/admin/img/product/no-image.png') }}"
                                                                width="20" alt="Ảnh sản phẩm">
                                                        </td>
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
                                                    <th>Stt</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Ảnh</th>
                                                    <th>Lượt yêu thích</th>
                                                </tr>
                                            </thead>
                                            <tbody id="topProductsByFavorites">
                                                @forelse($topProductsByFavorites as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/admin/img/product/no-image.png') }}"
                                                                width="20" alt="Ảnh sản phẩm">
                                                        </td>
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
                    $periodLabels = $revenueByPeriod->pluck('label');
                    $periodData = $revenueByPeriod->pluck('total');
                    $statusLabels = $orderStatusStats->pluck('name');
                    $statusData = $orderStatusStats->pluck('total');
                @endphp

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

                <script>
                    let revenueChart, orderStatusChart, paymentChart;

                    document.addEventListener("DOMContentLoaded", function() {
                        const form = document.getElementById("filterForm");

                        // --- Khởi tạo biểu đồ doanh thu ---
                        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                        revenueChart = new Chart(revenueCtx, {
                            type: 'line',
                            data: {
                                labels: @json($periodLabels),
                                datasets: [{
                                    label: 'Doanh thu (VND)',
                                    data: @json($periodData),
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: '#4bc0c0',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true
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
                                    x: {
                                        ticks: {
                                            maxRotation: 0,
                                            minRotation: 0,
                                            autoSkip: true,
                                            autoSkipPadding: 10
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(v) {
                                                return v.toLocaleString('vi-VN') + " đ";
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        // --- Khởi tạo biểu đồ trạng thái đơn hàng ---
                        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
                        orderStatusChart = new Chart(statusCtx, {
                            type: 'doughnut',
                            data: {
                                labels: @json($statusLabels),
                                datasets: [{
                                    data: @json($statusData),
                                    backgroundColor: [
                                        '#36A2EB', '#FF9F40', '#FFCE56', '#4BC0C0',
                                        '#9966FF', '#FF6384', '#FF4444', '#44FF44'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            generateLabels: (chart) => {
                                                const data = chart.data.datasets[0].data;
                                                const total = data.reduce((a, b) => a + b, 0);

                                                // Các legend bình thường: label + số lượng + %
                                                const items = chart.data.labels.map((label, i) => ({
                                                    text: `${label} (${data[i]} đơn - ${((data[i]/total)*100).toFixed(1)}%)`,
                                                    fillStyle: chart.data.datasets[0].backgroundColor[
                                                        i],
                                                    strokeStyle: chart.data.datasets[0].backgroundColor[
                                                        i],
                                                    index: i
                                                }));

                                                // Thêm legend tổng ở cuối
                                                items.push({
                                                    text: `Tổng: ${total} đơn`,
                                                    fillStyle: '#ffffff',
                                                    strokeStyle: '#ffffff',
                                                    index: data.length
                                                });

                                                return items;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        const paymentData = @json($paymentStats);

                        if (document.getElementById('paymentChart')) {
                            const ctx = document.getElementById('paymentChart').getContext('2d');
                            const totalOrders = paymentData.reduce((sum, p) => sum + p.total_orders, 0);
                            document.getElementById('paymentTotal').innerText = `Tổng tất cả: ${totalOrders} đơn`;

                            paymentChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: paymentData.map(p => `${p.method} (${p.total_orders} đơn)`),
                                    datasets: [{
                                        data: paymentData.map(p => p.total_orders),
                                        backgroundColor: ['#f39c12', '#00c0ef', '#00a65a']
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                generateLabels: function(chart) {
                                                    const data = chart.data;
                                                    const dataset = data.datasets[0];
                                                    const total = dataset.data.reduce((a, b) => a + b, 0);

                                                    return data.labels.map((label, i) => {
                                                        let value = dataset.data[i];
                                                        let percentage = ((value / total) * 100).toFixed(
                                                            1) + '%';
                                                        return {
                                                            text: `${label} - ${percentage}`,
                                                            fillStyle: dataset.backgroundColor[i]
                                                        };
                                                    });
                                                }
                                            }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                    let value = context.raw;
                                                    let percentage = ((value / total) * 100).toFixed(1) + '%';
                                                    return `${context.label}: ${value} đơn (${percentage})`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                        // --- Hàm cập nhật dashboard ---
                        function updateDashboard(data) {
                            // 4 box nhỏ
                            document.querySelector(".revenue-value").innerText = new Intl.NumberFormat('vi-VN').format(data
                                .revenue) + "đ";
                            document.querySelector(".order-count").innerText = data.orderCount;
                            document.querySelector(".product-count").innerText = data.productCount;
                            document.querySelector(".user-count").innerText = data.userCount;

                            // Charts
                            revenueChart.data.labels = data.periodLabels;
                            revenueChart.data.datasets[0].data = data.periodData;
                            revenueChart.update();

                            orderStatusChart.data.labels = data.statusLabels;
                            orderStatusChart.data.datasets[0].data = data.statusData;
                            orderStatusChart.update();

                            paymentChart.data.labels = data.paymentStats.map(p => `${p.method} (${p.total_orders} đơn)`);
                            paymentChart.data.datasets[0].data = data.paymentStats.map(p => p.total_orders);
                            paymentChart.update();
                            let totalPaymentOrders = data.paymentStats.reduce((sum, p) => sum + p.total_orders, 0);
                            document.getElementById('paymentTotal').innerText = `Tổng tất cả: ${totalPaymentOrders} đơn`;
                            // Top Customers
                            let tbodyCustomer = document.querySelector("#topCustomers");
                            tbodyCustomer.innerHTML = "";
                            data.topCustomers.forEach((item, index) => {
                                tbodyCustomer.innerHTML += `
                                <tr>
                                    <td>${index+1}</td>
                                    <td><img src="${item.avatar}" width="40" class="rounded-circle"></td>
                                    <td>#${item.id}</td>
                                    <td>${item.name}</td>
                                    <td>${new Intl.NumberFormat('vi-VN').format(item.total_amount)} đ</td>
                                    <td>${item.total_orders}</td>
                                </tr>`;
                            });
                            // --- Cập nhật bảng Mã khuyến mãi ---
                            let tbodyCoupon = document.querySelector("#topCoupons");
                            if (tbodyCoupon) {
                                tbodyCoupon.innerHTML = "";
                                if (data.topCoupons.length) {
                                    data.topCoupons.forEach(coupon => {
                                        tbodyCoupon.innerHTML += `
                                        <tr>
                                            <td>${coupon.code}</td>
                                            <td>
                                                ${coupon.discount_type === 'percent'
                                                    ? coupon.value + '%'
                                                    : new Intl.NumberFormat('vi-VN').format(coupon.value) + ' đ'}
                                            </td>
                                            <td>${coupon.total_uses}</td>
                                            <td>${new Intl.NumberFormat('vi-VN').format(coupon.total_revenue)} đ</td>
                                        </tr>`;
                                    });
                                } else {
                                    tbodyCoupon.innerHTML = `
                                    <tr>
                                        <td colspan="4" class="text-center">Chưa có dữ liệu</td>
                                    </tr>`;
                                }
                            }
                            // Top Products By Sales
                            let tbodySales = document.querySelector("#topProductsBySales");
                            tbodySales.innerHTML = "";
                            data.topProductsBySales.forEach((item, index) => {
                                tbodySales.innerHTML += `
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${item.name}</td>
                                    <td><img src="${item.thumbnail}" width="20"></td>
                                    <td>${item.total}</td>
                                </tr>`;
                            });

                            // Top Products By Favorites
                            let tbodyFav = document.querySelector("#topProductsByFavorites");
                            tbodyFav.innerHTML = "";
                            data.topProductsByFavorites.forEach((item, index) => {
                                tbodyFav.innerHTML += `
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${item.name}</td>
                                    <td><img src="${item.thumbnail}" width="20"></td>
                                    <td>${item.total}</td>
                                </tr>`;
                            });
                        }

                        // --- Gọi AJAX khi thay đổi filter ---
                        form.addEventListener("submit", function(e) {
                            e.preventDefault();
                            let params = new URLSearchParams(new FormData(this)).toString();
                            fetch("{{ route('admin.dashboard') }}?" + params, {
                                    headers: {
                                        "X-Requested-With": "XMLHttpRequest"
                                    }
                                })
                                .then(res => res.json())
                                .then(data => updateDashboard(data));
                        });

                        // Trigger AJAX khi thay đổi select hoặc date
                        form.querySelectorAll("select, input[type=date]").forEach(el => {
                            el.addEventListener("change", () => form.dispatchEvent(new Event("submit")));
                        });
                    });
                </script>
            @endpush
        @endsection
