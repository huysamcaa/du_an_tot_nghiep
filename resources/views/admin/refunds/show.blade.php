@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Chi tiết hoàn tiền #R{{ $refund->id }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li><a href="{{ route('admin.refunds.index') }}">Yêu cầu hoàn tiền</a></li>
                            <li class="active">Chi tiết</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">

        {{-- Thông báo --}}
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

        {{-- Tóm tắt --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tóm tắt yêu cầu</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-2"><strong>Tổng tiền:</strong>
                        <div class="text-danger">{{ number_format($refund->total_amount, 0, ',', '.') }} đ</div>
                    </div>
                    <div class="col-md-4 mb-2"><strong>Trạng thái:</strong> <span class="badge badge-{{ $statusColors[$refund->status] ?? 'secondary' }}">{{ $statusLabels[$refund->status] ?? 'Không xác định' }}</span></div>
                    <div class="col-md-4 mb-2"><strong>Trạng thái TK:</strong> <span class="badge badge-{{ $bankStatusColors[$refund->bank_account_status] ?? 'secondary' }}">{{ $bankLabels[$refund->bank_account_status] ?? 'Không xác định' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Thông tin khách hàng & chi tiết --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Thông tin khách hàng & ngân hàng</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Khách hàng:</strong> {{ optional($refund->user)->name ?? '-' }}</p>
                        <p><strong>Điện thoại:</strong> {{ $refund->phone_number }}</p>
                        <p><strong>Email:</strong> {{ optional($refund->user)->email ?? '-' }}</p>
                        <p><strong>Số TK:</strong> {{ $refund->bank_account }}</p>
                        <p><strong>Chủ TK:</strong> {{ $refund->user_bank_name }}</p>
                        <p><strong>Ngân hàng:</strong> {{ $refund->bank_name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Chi tiết yêu cầu hoàn tiền</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Lý do:</strong> {{ $refund->reason }}</p>
                        @if($refund->reason_image)
                        <p><strong>Ảnh hoặc video từ khách:</strong><br>
                            @php
                            $ext = pathinfo($refund->reason_image, PATHINFO_EXTENSION);
                            $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                            @endphp

                            @if(in_array(strtolower($ext), $videoExtensions))
                            <video controls style="max-width: 30%; max-height: 30%;">
                                <source src="{{ asset('storage/' . $refund->reason_image) }}" type="video/{{ $ext }}">
                                Trình duyệt không hỗ trợ video.
                            </video>
                            @else
                            <img src="{{ asset('storage/' . $refund->reason_image) }}" class="img-fluid rounded" style="max-width:160px;">
                            @endif
                        </p>
                        @endif

                        {{-- Hiển thị lý do từ chối (admin_reason) --}}
                        @if($refund->admin_reason)
                            <p><strong>Lý do từ chối:</strong> {{ $refund->admin_reason }}</p>
                        @endif

                        {{-- Hiển thị lý do thất bại (fail_reason) --}}
                        @if($refund->fail_reason)
                            <p><strong>Lý do thất bại:</strong> {{ $refund->fail_reason }}</p>
                        @endif

                        {{-- Hiển thị ảnh bằng chứng giao dịch từ admin --}}
                        @if($refund->img_fail_or_completed)
                        <p><strong>Bằng chứng giao dịch từ admin:</strong><br>
                            @php
                                $ext = pathinfo($refund->img_fail_or_completed, PATHINFO_EXTENSION);
                                $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];
                            @endphp

                            @if(in_array(strtolower($ext), $videoExtensions))
                                <video controls style="max-width: 100%; max-height: 300px;">
                                    <source src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" type="video/{{ $ext }}">
                                    Trình duyệt không hỗ trợ video.
                                </video>
                            @else
                                <img src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" class="img-fluid rounded" style="max-width:160px;">
                            @endif
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Danh sách sản phẩm hoàn --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Sản phẩm yêu cầu hoàn ({{ $refund->items->count() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 text-center align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Biến thể</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($refund->items as $item)
                            <tr>
                                <td>
                                    @if(optional($item->variant)->thumbnail)
                                    <img src="{{ asset('storage/' . $item->variant->thumbnail) }}" style="width:36px;">
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td> @if($item->variant && $item->variant->attributeValues->count())
                                    {{ $item->variant->attributeValues->pluck('value')->implode(' - ') }}
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

         {{-- Form cập nhật --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Cập nhật trạng thái</h5>
            </div>
            <div class="card-body">
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
                $allStatuses = ['pending','receiving','completed','rejected','failed','cancel'];
                $allBanks = ['unverified','verified','sent'];
                $isCompletedOrFinal = in_array($refund->status, ['completed', 'rejected', 'failed', 'cancel']);
                @endphp

                <form method="POST" action="{{ route('admin.refunds.update', $refund) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Các trường dropdown trạng thái --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="status">Trạng thái yêu cầu</label>
                            <select name="status" id="status" class="form-control form-control-sm" {{ $isCompletedOrFinal ? 'disabled' : '' }}>
                                <option value="{{ $refund->status }}" selected>{{ $statusLabels[$refund->status] }}</option>
                                @if(!$isCompletedOrFinal)
                                    @foreach($allowedStatuses as $key)
                                        <option value="{{ $key }}">{{ $statusLabels[$key] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bank_account_status">Trạng thái TK ngân hàng</label>
                            <select name="bank_account_status" id="bank_account_status" class="form-control form-control-sm" {{ $isCompletedOrFinal ? 'disabled' : '' }}>
                                <option value="{{ $refund->bank_account_status }}" selected>{{ $bankLabels[$refund->bank_account_status] }}</option>
                                @if(!$isCompletedOrFinal)
                                    @foreach($allowedBanks as $key)
                                        <option value="{{ $key }}">{{ $bankLabels[$key] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Trường admin_reason (Lý do từ chối) --}}
                    <div class="form-group" id="admin_reason_group" style="{{ $refund->status === 'rejected' ? 'display:block;' : 'display:none;' }}">
                        <label for="admin_reason">Lý do từ chối *</label>
                        <textarea name="admin_reason" id="admin_reason" class="form-control form-control-sm" rows="2" placeholder="Nhập lý do từ chối">{{ old('admin_reason', $refund->admin_reason) }}</textarea>
                    </div>

                    {{-- Trường fail_reason (Lý do thất bại) --}}
                    <div class="form-group" id="fail_reason_group" style="{{ $refund->status === 'failed' ? 'display:block;' : 'display:none;' }}">
                        <label for="fail_reason">Lý do thất bại *</label>
                        <textarea name="fail_reason" id="fail_reason" class="form-control form-control-sm" rows="2" placeholder="Nhập lý do giao dịch thất bại">{{ old('fail_reason', $refund->fail_reason) }}</textarea>
                    </div>

                    {{-- Checkbox "Đã chuyển tiền" --}}
                    <div class="form-group form-check" id="is_send_money_group" style="{{ $refund->status === 'completed' ? 'display:block;' : 'display:none;' }}">
                        <input type="checkbox" name="is_send_money" class="form-check-input" id="is_send_money" value="1" {{ $refund->is_send_money ? 'checked' : '' }} {{ $isCompletedOrFinal ? 'disabled' : '' }}>
                        <label class="form-check-label" for="is_send_money">Đã chuyển tiền</label>
                    </div>

                    {{-- Trường upload ảnh bằng chứng --}}
                    <div class="form-group" id="img_upload_group" style="{{ in_array($refund->status, ['completed', 'failed']) ? 'display:block;' : 'display:none;' }}">
                        <label id="img_upload_label" for="img_fail_or_completed">
                            @if($refund->status === 'completed')
                                Ảnh chuyển khoản
                            @elseif($refund->status === 'failed')
                                Ảnh chứng minh thất bại
                            @else
                                Ảnh chứng minh
                            @endif
                            @if(in_array($refund->status, ['completed', 'failed'])) * @endif
                        </label>
                        <input type="file" name="img_fail_or_completed" id="img_fail_or_completed" accept="image/*,video/*" class="form-control-file form-control-sm" {{ $isCompletedOrFinal ? 'disabled' : '' }}>
                        <small class="form-text text-muted" id="img_upload_help">Vui lòng tải lên ảnh/video chứng minh.</small>
                    </div>

                    {{-- Nút cập nhật --}}
                    @if(!$isCompletedOrFinal)
                        <button type="submit" class="btn btn-primary btn-sm"> <i class="fa fa-save"></i> Cập nhật</button>
                    @else
                        <button type="button" class="btn btn-success btn-sm disabled"><i class="fa fa-check"></i> Đã hoàn thành</button>
                    @endif
                    <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Quay lại danh sách</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    jQuery(document).ready(function($) {
        const statusSelect = $('#status');
        const bankStatusSelect = $('#bank_account_status');
        const isSendMoneyCheckbox = $('#is_send_money');
        const isSendMoneyGroup = $('#is_send_money_group');
        const imgUploadGroup = $('#img_upload_group');
        const imgUploadLabel = $('#img_upload_label');
        const adminReasonGroup = $('#admin_reason_group');
        const failReasonGroup = $('#fail_reason_group');

        function updateFormState() {
            const currentStatus = statusSelect.val();

            // Ẩn/hiện các trường dựa trên trạng thái chính
            adminReasonGroup.hide();
            failReasonGroup.hide();
            isSendMoneyGroup.hide();
            imgUploadGroup.hide();

            switch (currentStatus) {
                case 'completed':
                    isSendMoneyGroup.show();
                    imgUploadGroup.show();
                    imgUploadLabel.html('Ảnh chuyển khoản <span class="text-danger">*</span>');
                    break;
                case 'failed':
                    failReasonGroup.show();
                    imgUploadGroup.show();
                    imgUploadLabel.html('Ảnh chứng minh thất bại <span class="text-danger">*</span>');
                    break;
                case 'rejected':
                    adminReasonGroup.show();
                    break;
            }

            // Tự động đồng bộ trạng thái ngân hàng khi tích "Đã chuyển tiền"
            if (isSendMoneyCheckbox.is(':checked')) {
                bankStatusSelect.val('sent').trigger('change');
            }
        }

        // Sự kiện khi thay đổi trạng thái chính
        statusSelect.on('change', updateFormState);

        // Sự kiện khi tích/bỏ tích checkbox "Đã chuyển tiền"
        isSendMoneyCheckbox.on('change', function() {
            if ($(this).is(':checked')) {
                if (statusSelect.val() === 'completed') {
                    bankStatusSelect.val('sent').trigger('change');
                }
            }
        });

        // Cập nhật trạng thái form ngay khi load trang
        updateFormState();
    });
</script>
@endpush
