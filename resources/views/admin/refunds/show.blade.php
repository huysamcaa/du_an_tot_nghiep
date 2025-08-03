@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-2">
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h4 class="mb-0">Chi tiết Hoàn tiền #R{{ $refund->id }}</h4>
        </div>
        <div class="col-md-6 text-right">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.refunds.index') }}">Yêu cầu hoàn tiền</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </div>
    </div>

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

    {{-- Tóm tắt trạng thái - 3 cục bằng nhau --}}
    <div class="row mb-3">
        <div class="col-md-4 mb-2 d-flex">
            <div class="card shadow-sm flex-fill h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-center">
                    <div class="w-100 text-center">
                        <strong>Tổng tiền hoàn:</strong>
                        <span class="text-danger">{{ number_format($refund->total_amount, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2 d-flex">
            <div class="card shadow-sm flex-fill h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-center">
                    <span class="badge badge-{{ $statusColors[$refund->status] ?? 'secondary' }}">
                        {{ $statusLabels[$refund->status] ?? 'Không xác định' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2 d-flex">
            <div class="card shadow-sm flex-fill h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-center">
                    <span class="badge badge-{{ $bankStatusColors[$refund->bank_account_status] ?? 'secondary' }}">
                        {{ $bankLabels[$refund->bank_account_status] ?? 'Không xác định' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6 mb-2">
            <div class="card shadow-sm h-100">
                <div class="card-header py-2"><strong>Thông tin khách hàng & Ngân hàng</strong></div>
                <div class="card-body py-2 px-3">
                    <div class="mb-1"><strong>Khách hàng:</strong> {{ optional($refund->user)->name ?? '-' }}</div>
                    <div class="mb-1"><strong>Điện thoại:</strong> {{ $refund->phone_number }}</div>
                    <div class="mb-1"><strong>Email:</strong> {{ optional($refund->user)->email ?? '-' }}</div>
                    <div class="mb-1"><strong>Số TK:</strong> {{ $refund->bank_account }}</div>
                    <div class="mb-1"><strong>Chủ TK:</strong> {{ $refund->user_bank_name }}</div>
                    <div><strong>Ngân hàng:</strong> {{ $refund->bank_name }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="card shadow-sm h-100">
                <div class="card-header py-2"><strong>Chi tiết yêu cầu hoàn tiền</strong></div>
                <div class="card-body py-2 px-3">
                    <div class="mb-1"><strong>Lý do khách hàng:</strong> {{ $refund->reason }}</div>
                    @if($refund->reason_image)
                    <div class="mb-1"><strong>Ảnh minh chứng:</strong><br>
                        <img src="{{ asset('storage/' . $refund->reason_image) }}" class="img-fluid rounded" style="max-width: 160px;">
                    </div>
                    @endif
                    @if($refund->fail_reason)
                    <div class="mb-1"><strong>Lý do lỗi từ admin:</strong> <span class="text-danger">{{ $refund->fail_reason }}</span></div>
                    @endif
                    @if($refund->img_fail_or_completed)
                    <div><strong>Ảnh trạng thái:</strong><br>
                        <img src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" class="img-fluid rounded" style="max-width: 160px;">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sản phẩm hoàn --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header py-2"><strong>Sản phẩm yêu cầu hoàn</strong></div>
        <div class="card-body py-2 px-3">
            <table class="table table-sm table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width:50px;">Ảnh</th>
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
                            @if(optional($item->product)->thumbnail)
                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" style="width:36px;">
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->name_variant ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Form cập nhật --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-2"><strong>Cập nhật trạng thái</strong></div>
        <div class="card-body py-2 px-3">
            @php
                $statusTransitions = [
                    'pending' => ['receiving','cancel','failed','rejected'],
                    'receiving' => ['completed','cancel','failed','rejected'],
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
                $currentStatus = $refund->status;
                $currentBank = $refund->bank_account_status;
                $allowedStatuses = $statusTransitions[$currentStatus] ?? [];
                $allStatuses = ['pending','receiving','completed','rejected','failed','cancel'];
                $allowedBanks = $bankTransitions[$currentBank] ?? [];
                $allBanks = ['unverified','verified','sent'];

                $statusLabels = [
                    'pending' => 'Chờ xử lý',
                    'receiving' => 'Đang tiếp nhận',
                    'completed' => 'Hoàn thành',
                    'rejected' => 'Đã từ chối',
                    'failed' => 'Thất bại',
                    'cancel' => 'Đã hủy',
                ];
                $bankLabels = [
                    'unverified' => 'Chưa xác minh',
                    'verified' => 'Đã xác minh',
                    'sent' => 'Đã gửi',
                ];
            @endphp
            <form method="POST" action="{{ route('admin.refunds.update', $refund) }}">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-6 mb-2">
                        <label for="status">Trạng thái yêu cầu</label>
                        <select name="status" id="status" class="form-control form-control-sm">
                            <optgroup label="Có thể chọn">
                                @foreach($allowedStatuses as $key)
                                <option value="{{ $key }}">{{ $statusLabels[$key] }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Không thể chọn">
                                @foreach(array_diff($allStatuses, $allowedStatuses) as $key)
                                <option value="{{ $key }}" disabled {{ $currentStatus == $key ? 'selected' : '' }}>{{ $statusLabels[$key] }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        <label for="bank_account_status">Trạng thái TK ngân hàng</label>
                        <select name="bank_account_status" id="bank_account_status" class="form-control form-control-sm">
                            <optgroup label="Có thể chọn">
                                @foreach($allowedBanks as $key)
                                <option value="{{ $key }}">{{ $bankLabels[$key] }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Không thể chọn">
                                @foreach(array_diff($allBanks, $allowedBanks) as $key)
                                <option value="{{ $key }}" disabled {{ $currentBank == $key ? 'selected' : '' }}>{{ $bankLabels[$key] }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label for="admin_reason">Ghi chú từ Admin</label>
                    <textarea name="admin_reason" id="admin_reason" class="form-control form-control-sm" rows="2" placeholder="Nhập ghi chú...">{{ old('admin_reason', $refund->admin_reason) }}</textarea>
                </div>
                <div class="form-group form-check mb-2">
                    <input type="checkbox" name="is_send_money" class="form-check-input" id="is_send_money" value="1" {{ $refund->is_send_money ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_send_money">Đã chuyển tiền</label>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
