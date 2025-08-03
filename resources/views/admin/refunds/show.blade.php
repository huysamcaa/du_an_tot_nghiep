@extends('admin.layouts.app')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h3>Chi tiết Hoàn tiền #R{{ $refund->id }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Trang chủ</a></li>
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
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Tóm tắt trạng thái --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="mb-0">Tổng tiền hoàn: <strong class="text-danger">{{ number_format($refund->total_amount, 0, ',', '.') }} đ</strong></h4>
                            </div>
                            <div class="col-md-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'secondary',
                                        'receiving' => 'info',
                                        'completed' => 'success',
                                        'rejected' => 'danger',
                                        'failed' => 'warning',
                                        'cancel' => 'dark',
                                    ];
                                @endphp
                                <p class="mb-0">Trạng thái yêu cầu: <span class="badge badge-{{ $statusColors[$refund->status] ?? 'secondary' }}">{{ $statusLabels[$refund->status] ?? 'Không xác định' }}</span></p>
                            </div>
                            <div class="col-md-4 text-right">
                                @php
                                    $bankStatusColors = [
                                        'unverified' => 'secondary',
                                        'verified' => 'info',
                                        'sent' => 'success',
                                    ];
                                @endphp
                                <p class="mb-0">Trạng thái TK ngân hàng: <span class="badge badge-{{ $bankStatusColors[$refund->bank_account_status] ?? 'secondary' }}">{{ $bankLabels[$refund->bank_account_status] ?? 'Không xác định' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Thông tin khách hàng & Chi tiết yêu cầu --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header"><strong>Thông tin khách hàng & Tài khoản ngân hàng</strong></div>
                    <div class="card-body">
                        <p><strong>Khách hàng:</strong> {{ optional($refund->user)->name ?? '-' }}</p>
                        <p><strong>Điện thoại:</strong> {{ $refund->phone_number }}</p>
                        <p><strong>Email:</strong> {{ optional($refund->user)->email ?? '-' }}</p>
                        <hr>
                        <p><strong>Số TK:</strong> {{ $refund->bank_account }}</p>
                        <p><strong>Chủ TK:</strong> {{ $refund->user_bank_name }}</p>
                        <p><strong>Ngân hàng:</strong> {{ $refund->bank_name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header"><strong>Chi tiết yêu cầu hoàn tiền</strong></div>
                    <div class="card-body">
                        <p><strong>Lý do khách hàng:</strong> {{ $refund->reason }}</p>

                        @if($refund->reason_image)
                        <p><strong>Ảnh minh chứng:</strong><br>
                            <a href="{{ asset('storage/' . $refund->reason_image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $refund->reason_image) }}" class="img-fluid" style="max-width: 200px;">
                            </a>
                        </p>
                        @endif

                        @if($refund->fail_reason)
                        <p><strong>Lý do lỗi từ admin:</strong> <span class="text-danger">{{ $refund->fail_reason }}</span></p>
                        @endif

                        @if($refund->img_fail_or_completed)
                        <p><strong>Ảnh trạng thái:</strong><br>
                            <a href="{{ asset('storage/' . $refund->img_fail_or_completed) }}" target="_blank">
                                <img src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" class="img-fluid" style="max-width: 200px;">
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sản phẩm hoàn --}}
        <div class="card mb-4">
            <div class="card-header"><strong>Sản phẩm yêu cầu hoàn</strong></div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead>
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
                            <td class="align-middle">
                                @if(optional($item->product)->thumbnail)
                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" style="width:60px;">
                                @else
                                -
                                @endif
                            </td>
                            <td class="align-middle">{{ $item->name }}</td>
                            <td class="align-middle">{{ $item->name_variant ?? '-' }}</td>
                            <td class="align-middle">{{ $item->quantity }}</td>
                            <td class="align-middle">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td class="align-middle">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form cập nhật --}}
        <div class="card mb-5">
            <div class="card-header"><strong>Cập nhật trạng thái</strong></div>
            <div class="card-body">
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Trạng thái yêu cầu</label>
                                <select name="status" id="status" class="form-control">
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_account_status">Trạng thái TK ngân hàng</label>
                                <select name="bank_account_status" id="bank_account_status" class="form-control">
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
                    </div>

                    <div class="form-group">
                        <label for="admin_reason">Ghi chú từ Admin</label>
                        <textarea name="admin_reason" id="admin_reason" class="form-control" rows="3" placeholder="Nhập ghi chú...">{{ old('admin_reason', $refund->admin_reason) }}</textarea>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" name="is_send_money" class="form-check-input" id="is_send_money" value="1" {{ $refund->is_send_money ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_send_money">Đã chuyển tiền</label>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
