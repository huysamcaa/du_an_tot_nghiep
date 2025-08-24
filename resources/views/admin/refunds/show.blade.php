@extends('admin.layouts.app')

@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết yêu cầu hoàn tiền</h4>
            <h6>Thông tin chi tiết về yêu cầu hoàn tiền #R{{ $refund->id }}</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.refunds.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
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

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{-- Cột trái: Thông tin chi tiết & Sản phẩm --}}
                <div class="col-lg-8">
                    {{-- Card gộp Thông tin khách hàng & Chi tiết yêu cầu --}}
                    <div class="card p-4 mb-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3"><i class="fas fa-circle-user me-2"></i>Thông tin khách hàng</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-muted">Khách hàng</th>
                                                <td><span class="fw-semibold">{{ optional($refund->user)->name ?? '-' }}</span></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Điện thoại</th>
                                                <td>{{ $refund->phone_number }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Email</th>
                                                <td>{{ optional($refund->user)->email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Chủ tài khoản</th>
                                                <td>{{ $refund->user_bank_name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Số tài khoản</th>
                                                <td><span class="fw-semibold">{{ $refund->bank_account }}</span></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Ngân hàng</th>
                                                <td>{{ $refund->bank_name }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3"><i class="fas fa-list-ul me-2"></i>Chi tiết yêu cầu</h5>
                                <p class="text-muted mb-2"><strong>Lý do yêu cầu:</strong> {{ $refund->reason }}</p>
                                @if($refund->reason_image)
                                <p class="mb-2"><strong>Ảnh/video từ khách hàng:</strong></p>
                                @php
                                $ext = pathinfo($refund->reason_image, PATHINFO_EXTENSION);
                                $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                                @endphp
                                @if(in_array(strtolower($ext), $videoExtensions))
                                <video controls style="max-width: 100%; max-height: 250px;" class="rounded shadow-sm">
                                    <source src="{{ asset('storage/' . $refund->reason_image) }}" type="video/{{ $ext }}">
                                    Trình duyệt không hỗ trợ video.
                                </video>
                                @else
                                <a href="{{ asset('storage/' . $refund->reason_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $refund->reason_image) }}" class="img-fluid rounded shadow-sm" style="max-width:200px;">
                                </a>
                                @endif
                                @endif

                                @if($refund->admin_reason)
                                <hr>
                                <p class="mb-2"><strong>Lý do từ chối:</strong> <span class="text-danger">{{ $refund->admin_reason }}</span></p>
                                @endif
                                @if($refund->fail_reason)
                                <hr>
                                <p class="mb-2"><strong>Lý do thất bại:</strong> <span class="text-danger">{{ $refund->fail_reason }}</span></p>
                                @endif
                                @if($refund->img_fail_or_completed)
                                <hr>
                                <p class="mb-2"><strong>Bằng chứng giao dịch từ Admin:</strong></p>
                                @php
                                $ext = pathinfo($refund->img_fail_or_completed, PATHINFO_EXTENSION);
                                $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                                @endphp
                                @if(in_array(strtolower($ext), $videoExtensions))
                                <video controls style="max-width: 100%; max-height: 250px;" class="rounded shadow-sm">
                                    <source src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" type="video/{{ $ext }}">
                                    Trình duyệt không hỗ trợ video.
                                </video>
                                @else
                                <a href="{{ asset('storage/' . $refund->img_fail_or_completed) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" class="img-fluid rounded shadow-sm" style="max-width:200px;">
                                </a>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card danh sách sản phẩm --}}
                    <div class="card p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-box me-2"></i>Sản phẩm yêu cầu hoàn ({{ $refund->items->count() }})
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Biến thể</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($refund->items as $item)
                                    <tr>
                                        <td class="align-middle">
                                            @if(optional($item->variant)->thumbnail)
                                            <div class="product-image-small rounded overflow-hidden shadow-sm" style="width: 50px; height: 50px;">
                                                <img src="{{ asset('storage/' . $item->variant->thumbnail) }}" alt="Ảnh sản phẩm" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                            </div>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $item->name }}</td>
                                        <td class="align-middle">
                                            @if($item->variant && $item->variant->attributeValues->count())
                                            @foreach($item->variant->attributeValues as $value)
                                            <span class="badge bg-primary me-1">{{ $value->attribute->name }}: {{ $value->value }}</span>
                                            @endforeach
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">{{ $item->quantity }}</td>
                                        <td class="align-middle text-end">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                        <td class="align-middle text-end fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Cột phải: Tóm tắt & Cập nhật trạng thái --}}
                {{-- Đã di chuyển sticky-top ra ngoài card để tránh xung đột CSS --}}
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 70px;">
                        <div class="card shadow-sm">
                            <div class="card-header text-white" style="background-color: #ff9f43;">
                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Cập nhật hoàn tiền </h5>
                            </div>
                            <div class="card-body">
                                {{-- Tóm tắt trạng thái --}}
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold text-danger">{{ number_format($refund->total_amount, 0, ',', '.') }} đ</h4>
                                    <div class="text-muted mb-2">Tổng tiền hoàn trả</div>
                                    <div class="d-flex justify-content-center gap-2">
                                        <span class="badge bg-{{ $statusColors[$refund->status] ?? 'secondary' }} fs-6 px-3 py-2 me-1">{{ $statusLabels[$refund->status] ?? 'Không xác định' }}</span>
                                        <span class="badge bg-{{ $bankStatusColors[$refund->bank_account_status] ?? 'secondary' }} fs-6 px-3 py-2">{{ $bankLabels[$refund->bank_account_status] ?? 'Không xác định' }}</span>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Form cập nhật --}}
                                @php
                                $statusTransitions = [
                                'pending' => ['receiving', 'rejected', 'failed', 'cancel'],
                                'receiving' => ['completed', 'rejected', 'failed', 'cancel'],
                                'completed' => [],
                                'rejected' => [],
                                'failed' => [],
                                'cancel' => [],
                                ];
                                $bankTransitions = [
                                'unverified' => ['verified'],
                                'verified' => ['sent'],
                                'sent' => [],
                                ];
                                $allowedStatuses = $statusTransitions[$refund->status] ?? [];
                                $allowedBanks = $bankTransitions[$refund->bank_account_status] ?? [];
                                $isCompletedOrFinal = in_array($refund->status, ['completed', 'rejected', 'failed', 'cancel']);
                                @endphp

                                <h6 class="text-muted mb-3"><i class="fas fa-pen-to-square me-2"></i>Cập nhật trạng thái</h6>
                                <form method="POST" action="{{ route('admin.refunds.update', $refund) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái yêu cầu</label>
                                        <select name="status" id="status" class="form-select" {{ $isCompletedOrFinal ? 'disabled' : '' }}>
                                            <option value="{{ $refund->status }}" {{ old('status', $refund->status) === $refund->status ? 'selected' : '' }}>
                                                {{ $statusLabels[$refund->status] }}
                                            </option>
                                            @if(!$isCompletedOrFinal)
                                                @foreach($allowedStatuses as $key)
                                                    <option value="{{ $key }}" {{ old('status', $refund->status) === $key ? 'selected' : '' }}>
                                                        {{ $statusLabels[$key] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bank_account_status" class="form-label">Trạng thái TK ngân hàng</label>
                                        <select name="bank_account_status" id="bank_account_status" class="form-select" {{ $isCompletedOrFinal ? 'disabled' : '' }}>
                                            <option value="{{ $refund->bank_account_status }}" {{ old('bank_account_status', $refund->bank_account_status) === $refund->bank_account_status ? 'selected' : '' }}>
                                                {{ $bankLabels[$refund->bank_account_status] }}
                                            </option>
                                            @if(!$isCompletedOrFinal)
                                                @foreach($allowedBanks as $key)
                                                    <option value="{{ $key }}" {{ old('bank_account_status', $refund->bank_account_status) === $key ? 'selected' : '' }}>
                                                        {{ $bankLabels[$key] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    {{-- Trường admin_reason (Lý do từ chối) --}}
                                    <div class="mb-3" id="admin_reason_group" style="{{ (old('status', $refund->status) === 'rejected') ? 'display:block;' : 'display:none;' }}">
                                        <label for="admin_reason" class="form-label">Lý do từ chối *</label>
                                        <textarea name="admin_reason" id="admin_reason" class="form-control" rows="2" placeholder="Nhập lý do từ chối">{{ old('admin_reason', $refund->admin_reason) }}</textarea>
                                    </div>

                                    {{-- Trường fail_reason (Lý do thất bại) --}}
                                    <div class="mb-3" id="fail_reason_group" style="{{ (old('status', $refund->status) === 'failed') ? 'display:block;' : 'display:none;' }}">
                                        <label for="fail_reason" class="form-label">Lý do thất bại *</label>
                                        <textarea name="fail_reason" id="fail_reason" class="form-control" rows="2" placeholder="Nhập lý do giao dịch thất bại">{{ old('fail_reason', $refund->fail_reason) }}</textarea>
                                    </div>

                                    {{-- Trường upload ảnh bằng chứng --}}
                                    <div class="mb-3" id="img_upload_group" style="{{ in_array(old('status', $refund->status), ['completed', 'failed']) ? 'display:block;' : 'display:none;' }}">
                                        <label id="img_upload_label" for="img_fail_or_completed" class="form-label">
                                            @if($refund->status === 'completed')
                                            Ảnh chuyển khoản
                                            @elseif($refund->status === 'failed')
                                            Ảnh chứng minh thất bại
                                            @else
                                            Ảnh chứng minh
                                            @endif
                                            @if(in_array($refund->status, ['completed', 'failed'])) * @endif
                                        </label>
                                        <input type="file" name="img_fail_or_completed" id="img_fail_or_completed" accept="image/*,video/*" class="form-control" {{ $isCompletedOrFinal ? 'disabled' : '' }}>
                                        @error('img_fail_or_completed')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted" id="img_upload_help">Vui lòng tải lên ảnh/video chứng minh.</small>
                                    </div>


                                    {{-- Nút cập nhật --}}
                                    <div class="d-flex justify-content-end gap-2">
                                        @if(!$isCompletedOrFinal)
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Cập nhật</button>
                                        @else
                                        <button type="button" class="btn btn-success disabled"><i class="fas fa-check-circle me-1"></i> Đã hoàn thành</button>
                                        @endif
                                    </div>
                                </form>
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
<script>
    jQuery(document).ready(function($) {
        const statusSelect     = $('#status');
        const bankStatusSelect = $('#bank_account_status');
        const imgUploadGroup   = $('#img_upload_group');
        const imgUploadLabel   = $('#img_upload_label');
        const adminReasonGroup = $('#admin_reason_group');
        const failReasonGroup  = $('#fail_reason_group');
        const imgInput         = $('#img_fail_or_completed');

        function updateFormState() {
        const currentStatus = statusSelect.val();

        // reset
        adminReasonGroup.hide();
        failReasonGroup.hide();
        imgUploadGroup.hide();
        imgInput.prop('required', false);
        bankStatusSelect.prop('disabled', false); // reset dropdown bank

        switch (currentStatus) {
            case 'completed':
                imgUploadGroup.show();
                imgUploadLabel.html('Ảnh chuyển khoản <span class="text-danger">*</span>');
                imgInput.prop('required', true);

                // auto set bank_account_status = 'sent' và disable select
                bankStatusSelect.val('sent').prop('disabled', true);
                break;

            case 'failed':
                failReasonGroup.show();
                imgUploadGroup.show();
                imgUploadLabel.html('Ảnh chứng minh thất bại <span class="text-danger">*</span>');
                imgInput.prop('required', true);
                break;

            case 'rejected':
                adminReasonGroup.show();
                break;

            default:
                // các trạng thái khác, cho phép chỉnh bank bình thường
                break;
        }
    }


        statusSelect.on('change', updateFormState);

        // chạy ngay khi load (dựa theo old('status', ...) đã bind trong Blade)
        updateFormState();
    });
</script>
@endpush
