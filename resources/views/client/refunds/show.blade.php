@extends('client.layouts.app')

@section('title', 'Chi tiết hoàn đơn #' . $refund->order->code)

@section('content')
<section class="pageBannerSection">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pageBannerContent text-center">
                    <h2>Chi tiết hoàn đơn</h2>
                    <div class="pageBannerPath">
                        <a href="{{ route('client.home') }}">Trang chủ</a> &nbsp;&gt;&nbsp;
                        <span>Hoàn đơn #{{ $refund->order->code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">

        {{-- Danh sách sản phẩm hoàn --}}
        <div class="p-4 border rounded bg-white shadow-sm mb-4">
            <h5 class="mb-4"><i class="fas fa-box-open me-2 text-danger"></i>Sản phẩm hoàn</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Sản phẩm</th>
                            <th>Phân loại</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refund->items as $item)
                        <tr>
                            <td class="text-start d-flex align-items-center">
                                @if($item->variant)
                                    {{-- Hiển thị thông tin sản phẩm nếu variant còn tồn tại --}}
                                    <img src="{{ asset('storage/' . ($item->variant->thumbnail ?? 'default.jpg')) }}"
                                        alt="{{ $item->name ?? 'Sản phẩm' }}"
                                        class="me-3 rounded"
                                        style="width:50px; height:50px; object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold">{{ $item->name }}</div>
                                    </div>
                                @else
                                    {{-- Nếu variant đã bị xóa, hiển thị placeholder và thông báo --}}
                                    <div class="me-3 rounded d-flex align-items-center justify-content-center bg-light-subtle text-danger" style="width:50px; height:50px;">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Sản phẩm đã bị xóa</div>
                                        <small class="text-muted">-</small>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($item->variant && $item->variant->attributeValues->count())
                                {{ $item->variant->attributeValues->pluck('value')->implode(' - ') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price) }}₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-4">
            {{-- Thông tin chi tiết hoàn đơn --}}
            <div class="col-md-6">
                <div class="p-4 border rounded bg-white shadow-sm h-100">
                    <h5 class="mb-3"><i class="fas fa-file-invoice me-2 text-primary"></i>Thông tin hoàn đơn</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Mã đơn hàng:</strong> #{{ $refund->order->code }}</li>
                        <li class="list-group-item"><strong>Tổng tiền hoàn:</strong> {{ number_format($refund->total_amount) }}₫</li>
                        <li class="list-group-item">
                            <strong>Trạng thái:</strong>
                            @switch($refund->status)
                                @case('pending') <span class="badge bg-warning">Chờ xử lý</span> @break
                                @case('receiving') <span class="badge bg-info">Đang tiếp nhận</span> @break
                                @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                                @case('rejected') <span class="badge bg-danger">Đã từ chối</span> @break
                                @case('failed') <span class="badge bg-dark">Thất bại</span> @break
                                @case('cancel') <span class="badge bg-secondary">Đã hủy</span> @break
                                @default <span class="badge bg-light text-dark">{{ $refund->status }}</span>
                            @endswitch
                        </li>
                        <li class="list-group-item"><strong>Lý do hoàn:</strong> {{ $refund->reason }}</li>

                        @if($refund->status === 'rejected' && $refund->admin_reason)
                        <li class="list-group-item">
                            <strong>Lý do từ chối:</strong> <span class="text-danger">{{ $refund->admin_reason }}</span>
                        </li>
                        @endif
                        @if($refund->status === 'failed' && $refund->fail_reason)
                        <li class="list-group-item">
                            <strong>Lý do thất bại:</strong> <span class="text-danger">{{ $refund->fail_reason }}</span>
                        </li>
                        @endif

                        @if($refund->reason_image)
                        <li class="list-group-item">
                            <strong>Hình ảnh/Video minh chứng của bạn:</strong>
                            @php
                                $mediaPath = 'storage/' . $refund->reason_image;
                                $extension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));
                                $isVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
                            @endphp
                            <div class="mt-2">
                                @if($isVideo)
                                <video controls class="rounded w-100" style="max-height: 250px;">
                                    <source src="{{ asset($mediaPath) }}" type="video/{{ $extension }}">
                                    Trình duyệt của bạn không hỗ trợ video.
                                </video>
                                @else
                                <img src="{{ asset($mediaPath) }}" class="img-fluid rounded" style="max-height: 250px;">
                                @endif
                            </div>
                        </li>
                        @endif

                        @if(($refund->status === 'completed' || $refund->status === 'failed') && $refund->img_fail_or_completed)
                        <li class="list-group-item">
                            <strong>Hình ảnh từ Admin:</strong>
                            @php
                                $adminMediaPath = 'storage/' . $refund->img_fail_or_completed;
                                $adminExtension = strtolower(pathinfo($adminMediaPath, PATHINFO_EXTENSION));
                                $isAdminVideo = in_array($adminExtension, ['mp4', 'webm', 'ogg', 'mov']);
                            @endphp
                            <div class="mt-2">
                                @if($isAdminVideo)
                                <video controls class="rounded w-100" style="max-height: 250px;">
                                    <source src="{{ asset($adminMediaPath) }}" type="video/{{ $adminExtension }}">
                                    Trình duyệt của bạn không hỗ trợ video.
                                </video>
                                @else
                                <img src="{{ asset($adminMediaPath) }}" class="img-fluid rounded" style="max-height: 250px;">
                                @endif
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Thông tin tài khoản --}}
            <div class="col-md-6">
                <div class="p-4 border rounded bg-white shadow-sm h-100">
                    <h5 class="mb-3"><i class="fas fa-university me-2 text-success"></i>Thông tin tài khoản nhận tiền</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Ngân hàng:</strong> {{ $refund->bank_name }}</li>
                        <li class="list-group-item"><strong>Số tài khoản:</strong> {{ $refund->bank_account }}</li>
                        <li class="list-group-item"><strong>Chủ tài khoản:</strong> {{ $refund->user_bank_name }}</li>
                        <li class="list-group-item"><strong>SĐT liên hệ:</strong> {{ $refund->phone_number }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('refunds.index') }}" class="ulinaBTN">
                <span>Quay lại </span>
            </a>
        </div>

    </div>
</section>
@endsection
