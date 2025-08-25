@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết mã giảm giá</h4>
            <h6>Mã: <span class="text-primary fw-semibold">#{{ $coupon->code }}</span></h6>
        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2 px-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="card border-0 shadow-sm mx-n2 mx-lg-n3">
      <div class="card-body px-3 px-lg-4">

    <div class="row">
        {{-- Cột trái: Thông tin mã + Điều kiện & Phạm vi (theo style Refunds) --}}
        <div class="col-lg-8">
            {{-- Card: Thông tin mã giảm giá --}}
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin mã giảm giá</h5>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted">Tiêu đề</th>
                                <td>{{ $coupon->title ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Kiểu giảm giá</th>
                                <td class="text-capitalize">{{ $coupon->discount_type }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Giá trị giảm</th>
                                <td>
                                    <span class="fw-semibold text-danger">
                                        {{ $coupon->discount_type === 'percent'
                                            ? ((int) $coupon->discount_value . '%')
                                            : (number_format($coupon->discount_value, 0, ',', '.') . ' đ') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Giới hạn sử dụng</th>
                                <td>{{ $coupon->usage_limit !== null ? number_format($coupon->usage_limit, 0, ',', '.') : 'Không giới hạn' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Nhóm người dùng</th>
                                <td>
                                    <span class="badge bg-info">{{ $coupon->user_group ?: 'Tất cả' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Trạng thái</th>
                                <td>
                                    <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                        {{ $coupon->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Mô tả</th>
                                <td>{{ $coupon->description ?: '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Ngày tạo</th>
                                <td>{{ optional($coupon->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Ngày cập nhật</th>
                                <td>{{ optional($coupon->updated_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Card: Điều kiện & Phạm vi áp dụng --}}
            <div class="card p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-clipboard-check me-2"></i>Điều kiện & Phạm vi áp dụng</h5>

                {{-- Điều kiện --}}
                <div class="table-responsive mb-3">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted">Đơn hàng tối thiểu</th>
                                <td>
                                    @if(optional($coupon->restriction)->min_order_value)
                                        {{ number_format($coupon->restriction->min_order_value, 0, ',', '.') }} đ
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Số tiền giảm tối đa</th>
                                <td>
                                    @if(!is_null(optional($coupon->restriction)->max_discount_value))
                                        {{ number_format($coupon->restriction->max_discount_value, 0, ',', '.') }} đ
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Phạm vi: Danh mục & Sản phẩm --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2"><i class="fas fa-layer-group me-2"></i>Danh mục áp dụng</div>
                            @php $cats = ($categories ?? collect()); @endphp
                            @if($cats->count())
                                @foreach($cats as $category)
                                    <span class="badge bg-info me-1 mb-1">{{ $category->name }}</span>
                                @endforeach
                            @else
                                <div class="text-muted">Không có</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2"><i class="fas fa-tags me-2"></i>Sản phẩm áp dụng</div>
                            @php $prods = ($products ?? collect()); @endphp
                            @if($prods->count())
                                @foreach($prods as $product)
                                    <span class="badge bg-success me-1 mb-1">{{ $product->name }}</span>
                                @endforeach
                            @else
                                <div class="text-muted">Không có</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Cột phải: Sticky tóm tắt thời gian & trạng thái (theo style Refunds) --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 70px;">
                <div class="card shadow-sm">
                    <div class="card-header text-white" style="background-color:#ff9f43;">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Thời gian & Trạng thái</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted">Ngày bắt đầu</div>
                            <div class="fw-semibold">
                                {{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y H:i') : '—' }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Ngày kết thúc</div>
                            <div class="fw-semibold">
                                {{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y H:i') : '—' }}
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-{{ $coupon->is_expired ? 'warning text-dark' : 'secondary' }}">{{ $coupon->is_expired ? 'Có thời hạn' : 'Vô hạn' }}</span>
                            <span class="badge bg-{{ $coupon->is_notified ? 'primary' : 'light' }}">{{ $coupon->is_notified ? 'Đã thông báo' : 'Chưa thông báo' }}</span>
                            <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">{{ $coupon->is_active ? 'Kích hoạt' : 'Đã tắt' }}</span>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Chỉnh sửa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
             </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Không có thống kê / biểu đồ để load --}}
@endpush
