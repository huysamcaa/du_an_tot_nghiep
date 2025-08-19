@extends('admin.layouts.app')

@section('content')
<style>
    .sp-card{border:1px solid #eef0f2;border-radius:10px;background:#fff;overflow:hidden}
    .sp-card__hd{background:#ffa200;color:#fff;padding:12px 16px;font-weight:600}
    .sp-section{padding:0 16px 16px}
    .sp-row{display:flex;gap:16px;padding:12px 0;border-bottom:1px solid #f2f4f7;align-items:flex-start}
    .sp-row:last-child{border-bottom:none}
    .sp-label{width:220px;min-width:220px;color:#334155;font-weight:600;padding-top:4px}
    .sp-value{flex:1;color:#0f172a}
    .sp-badges .badge{margin:0 6px 6px 0}
    .table-sm td, .table-sm th{padding:.4rem .5rem;}

    /* KPI cards */
    .kpi{display:flex;align-items:center;gap:12px;border-radius:12px;padding:14px 16px;color:#0f172a;border:1px solid #eef0f2;background:#fff}
    .kpi__icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px}
    .kpi__meta{line-height:1}
    .kpi__val{font-weight:800;font-size:20px;margin-bottom:4px}
    .kpi__lbl{font-size:12px;color:#64748b}
    .bg-grad-blue{background:linear-gradient(135deg,#eff6ff,#dbeafe)}
    .bg-grad-green{background:linear-gradient(135deg,#ecfdf5,#d1fae5)}
    .bg-grad-yellow{background:linear-gradient(135deg,#fffbeb,#fef3c7)}
    .bg-grad-purple{background:linear-gradient(135deg,#faf5ff,#ede9fe)}
    .kpi__icon--blue{background:#e0f2fe}
    .kpi__icon--green{background:#dcfce7}
    .kpi__icon--yellow{background:#fef9c3}
    .kpi__icon--purple{background:#f3e8ff}

    /* List recent activity */
    .activity{list-style:none;margin:0;padding:0}
    .activity li{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px dashed #eef0f2}
    .activity li:last-child{border-bottom:none}
    .activity .meta{font-size:12px;color:#64748b}
</style>

@php
    /** Chuẩn hoá biến & fallback an toàn */
    $stats = $stats ?? ['claimed_total'=>0,'used_total'=>0,'usage_rate'=>0,'revenue'=>0];

    $timelineLabels = $timelineLabels ?? [];
    $timelineValues = $timelineValues ?? [];

    $groupBreakdown   = $groupBreakdown ?? [];
    $groupDonutLabels = ['guest','member','vip','khác'];
    $groupDonutData   = [
        $groupBreakdown['guest']  ?? 0,
        $groupBreakdown['member'] ?? 0,
        $groupBreakdown['vip']    ?? 0,
        $groupBreakdown['khác']   ?? ($groupBreakdown['other'] ?? 0),
    ];

    $recentUsage       = ($recentUsage ?? collect())->take(5);
    $ordersUsingCoupon = $ordersUsingCoupon ?? collect(); // giữ nếu cần sau này
@endphp

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết mã giảm giá</h4>
            <h6>Xem chi tiết mã #{{ $coupon->id }}</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
            <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary">
                <i class="fa fa-edit me-1"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs nav-tabs-solid mb-4" id="couponTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#pane-info"
                    type="button" role="tab" aria-controls="pane-info" aria-selected="true">
                <i class="fa fa-info-circle me-1"></i> Thông tin
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-condition" data-bs-toggle="tab" data-bs-target="#pane-condition"
                    type="button" role="tab" aria-controls="pane-condition" aria-selected="false">
                <i class="fa fa-clipboard-list me-1"></i> Điều kiện
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-scope" data-bs-toggle="tab" data-bs-target="#pane-scope"
                    type="button" role="tab" aria-controls="pane-scope" aria-selected="false">
                <i class="fa fa-tags me-1"></i> Phạm vi áp dụng
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-analytics" data-bs-toggle="tab" data-bs-target="#pane-analytics"
                    type="button" role="tab" aria-controls="pane-analytics" aria-selected="false">
                <i class="fa fa-chart-line me-1"></i> Thống kê
            </button>
        </li>
    </ul>

    <div class="tab-content" id="couponTabContent">
        {{-- TAB 1: Thông tin --}}
        <div class="tab-pane fade show active" id="pane-info" role="tabpanel" aria-labelledby="tab-info">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="sp-card mb-3">
                        <div class="sp-card__hd">Thông tin mã giảm giá</div>
                        <div class="sp-section">
                            <div class="sp-row">
                                <div class="sp-label">Mã</div>
                                <div class="sp-value fw-semibold">{{ $coupon->code }}</div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Tiêu đề</div>
                                <div class="sp-value">{{ $coupon->title ?? '—' }}</div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Giảm giá</div>
                                <div class="sp-value">
                                    <span class="text-danger fw-bold">
                                        {{ $coupon->discount_type === 'percent'
                                            ? ((int)$coupon->discount_value . '%')
                                            : (number_format($coupon->discount_value,0,',','.') . ' VNĐ') }}
                                    </span>
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Trạng thái</div>
                                <div class="sp-value">
                                    <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                        {{ $coupon->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                                    </span>
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Giới hạn sử dụng</div>
                                <div class="sp-value">{{ $coupon->usage_limit ?? 'Không giới hạn' }}</div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Nhóm người dùng</div>
                                <div class="sp-value">
                                    <span class="badge bg-info">{{ $coupon->user_group ?? 'Tất cả' }}</span>
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Ngày tạo</div>
                                <div class="sp-value">{{ optional($coupon->created_at)->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Ngày cập nhật</div>
                                <div class="sp-value">{{ optional($coupon->updated_at)->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Mô tả</div>
                                <div class="sp-value">{{ $coupon->description ?? 'Không có' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thời gian & thông báo --}}
                <div class="col-lg-6">
                    <div class="sp-card mb-3">
                        <div class="sp-card__hd">Thời gian & thông báo</div>
                        <div class="sp-section">
                            <div class="sp-row">
                                <div class="sp-label">Bắt đầu</div>
                                <div class="sp-value">
                                    {{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y H:i') : '--' }}
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Kết thúc</div>
                                <div class="sp-value">
                                    {{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y H:i') : '--' }}
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Thời gian</div>
                                <div class="sp-value">
                                    <span class="badge bg-{{ $coupon->is_expired ? 'warning text-dark' : 'secondary' }}">
                                        {{ $coupon->is_expired ? 'Có hạn' : 'Vô hạn' }}
                                    </span>
                                </div>
                            </div>
                            <div class="sp-row">
                                <div class="sp-label">Thông báo</div>
                                <div class="sp-value">
                                    <span class="badge bg-{{ $coupon->is_notified ? 'primary' : 'light' }}">
                                        {{ $coupon->is_notified ? 'Đã gửi' : 'Chưa gửi' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- /col --}}
            </div>
        </div>

        {{-- TAB 2: Điều kiện --}}
        <div class="tab-pane fade" id="pane-condition" role="tabpanel" aria-labelledby="tab-condition">
            <div class="sp-card">
                <div class="sp-card__hd">Điều kiện áp dụng</div>
                <div class="sp-section">
                    <div class="sp-row">
                        <div class="sp-label">Đơn hàng tối thiểu</div>
                        <div class="sp-value">
                            @if ($coupon->restriction && $coupon->restriction->min_order_value)
                                {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} VNĐ
                            @else — @endif
                        </div>
                    </div>
                    <div class="sp-row">
                        <div class="sp-label">Số tiền giảm tối đa</div>
                        <div class="sp-value">
                            @if ($coupon->restriction && !is_null($coupon->restriction->max_discount_value))
                                {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }} VNĐ
                            @else — @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 3: Phạm vi --}}
        <div class="tab-pane fade" id="pane-scope" role="tabpanel" aria-labelledby="tab-scope">
            <div class="sp-card mb-3">
                <div class="sp-card__hd">Danh mục áp dụng</div>
                <div class="sp-section">
                    <div class="sp-row">
                        <div class="sp-label">Danh mục</div>
                        <div class="sp-value sp-badges">
                            @forelse($categories as $category)
                                <span class="badge bg-info">{{ $category->name }}</span>
                            @empty
                                <span class="text-muted">Không có</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="sp-card">
                <div class="sp-card__hd">Sản phẩm áp dụng</div>
                <div class="sp-section">
                    <div class="sp-row">
                        <div class="sp-label">Sản phẩm</div>
                        <div class="sp-value sp-badges">
                            @forelse($products as $product)
                                <span class="badge bg-success">{{ $product->name }}</span>
                            @empty
                                <span class="text-muted">Không có</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 4: Thống kê (gọn đẹp) --}}
        <div class="tab-pane fade" id="pane-analytics" role="tabpanel" aria-labelledby="tab-analytics">
            {{-- KPI Row --}}
            <div class="row g-3 mb-3">
                <div class="col-6 col-md-3">
                    <div class="kpi bg-grad-blue">
                        <div class="kpi__icon kpi__icon--blue"><i class="fa fa-ticket-alt"></i></div>
                        <div class="kpi__meta">
                            <div class="kpi__val">{{ $stats['claimed_total'] }}</div>
                            <div class="kpi__lbl">Đã phát/nhận</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi bg-grad-green">
                        <div class="kpi__icon kpi__icon--green"><i class="fa fa-check-circle"></i></div>
                        <div class="kpi__meta">
                            <div class="kpi__val">{{ $stats['used_total'] }}</div>
                            <div class="kpi__lbl">Đã sử dụng</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi bg-grad-yellow">
                        <div class="kpi__icon kpi__icon--yellow"><i class="fa fa-percentage"></i></div>
                        <div class="kpi__meta">
                            <div class="kpi__val">{{ $stats['usage_rate'] }}%</div>
                            <div class="kpi__lbl">Tỷ lệ sử dụng</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi bg-grad-purple">
                        <div class="kpi__icon kpi__icon--purple"><i class="fa fa-dollar-sign"></i></div>
                        <div class="kpi__meta">
                            <div class="kpi__val">{{ number_format($stats['revenue'], 0, ',', '.') }} đ</div>
                            <div class="kpi__lbl">Doanh thu từ mã</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts + Recent Activity --}}
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="sp-card">
                        <div class="sp-card__hd">Lượt dùng theo ngày</div>
                        <div class="sp-section">
                            <canvas id="usageLine" height="130"></canvas>
                            @if(empty($timelineLabels))
                                <div class="text-muted mt-2">Chưa có dữ liệu sử dụng.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="sp-card">
                        <div class="sp-card__hd">Hoạt động gần đây</div>
                        <div class="sp-section">
                            @if($recentUsage->count())
                                <ul class="activity">
                                    @foreach($recentUsage as $row)
                                        <li>
                                            <div>
                                                <strong>{{ $row->user_name ?? 'Người dùng' }}</strong>
                                                @if(!empty($row->order_id))
                                                    <span class="text-primary">• Đơn #{{ $row->order_code ?? $row->order_id }}</span>
                                                @endif
                                                @if(!is_null($row->discount_applied))
                                                    <span class="text-success">• -{{ number_format($row->discount_applied,0,',','.') }} đ</span>
                                                @endif
                                            </div>
                                            <div class="meta">
                                                {{ !empty($row->used_at)
                                                    ? \Carbon\Carbon::parse($row->used_at)->format('d/m/Y H:i')
                                                    : (!empty($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '—') }}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-info mb-0">Chưa có hoạt động nào.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>{{-- /row --}}
        </div>
    </div>{{-- /tab-content --}}
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const lineCtx  = document.getElementById('usageLine');
            const donutCtx = document.getElementById('groupDonut');

            const lineLabels  = @json($timelineLabels);
            const lineValues  = @json($timelineValues);
            const donutLabels = @json($groupDonutLabels);
            const donutData   = @json($groupDonutData);

            if (lineCtx && Array.isArray(lineLabels) && lineLabels.length) {
                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: lineLabels,
                        datasets: [{
                            label: 'Lượt dùng',
                            data: lineValues,
                            tension: 0.35,
                            pointRadius: 2,
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                    }
                });
            }

            if (donutCtx) {
                new Chart(donutCtx, {
                    type: 'doughnut',
                    data: { labels: donutLabels, datasets: [{ data: donutData }] },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'bottom' } },
                        cutout: '60%'
                    }
                });
            }
        })();
    </script>
@endpush
