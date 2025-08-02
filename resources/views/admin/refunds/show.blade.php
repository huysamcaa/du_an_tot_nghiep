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
<div class="container">
    <div class="row mt-4 mb-2">
    </div>

    {{-- Alert --}}
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

    {{-- Thông tin khách hàng --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Thông tin khách hàng & Tài khoản ngân hàng</strong></div>
        <div class="card-body row">
            <div class="col-md-6">
                <p><strong>Khách hàng:</strong> {{ optional($refund->user)->name ?? '-' }}</p>
                <p><strong>Điện thoại:</strong> {{ $refund->phone_number }}</p>
                <p><strong>Email:</strong> {{ optional($refund->user)->email ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Số TK:</strong> {{ $refund->bank_account }}</p>
                <p><strong>Chủ TK:</strong> {{ $refund->user_bank_name }}</p>
                <p><strong>Ngân hàng:</strong> {{ $refund->bank_name }}</p>
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
                        <td>
                            @if(optional($item->product)->thumbnail)
                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" style="width:60px;">
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

    {{-- Chi tiết yêu cầu --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Chi tiết yêu cầu hoàn tiền</strong></div>
        <div class="card-body">
            <p><strong>Tổng tiền:</strong> {{ number_format($refund->total_amount, 0, ',', '.') }} đ</p>
            <p><strong>Lý do khách hàng:</strong> {{ $refund->reason }}</p>

            @if($refund->reason_image)
            <p><strong>Ảnh minh chứng:</strong><br>
                <img src="{{ asset('storage/' . $refund->reason_image) }}" class="img-fluid" style="max-width: 300px;">
            </p>
            @endif

            @if($refund->fail_reason)
            <p><strong>Lý do lỗi từ admin:</strong> {{ $refund->fail_reason }}</p>
            @endif

            @if($refund->img_fail_or_completed)
            <p><strong>Ảnh trạng thái:</strong><br>
                <img src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" class="img-fluid" style="max-width: 300px;">
            </p>
            @endif
        </div>
    </div>

    {{-- Form cập nhật --}}
    <div class="card mb-5">
        <div class="card-header"><strong>Cập nhật trạng thái</strong></div>
        <div class="card-body">
            @php
            // Định nghĩa ma trận chuyển trạng thái
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

            // Nhãn
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

                <div class="form-group">
                    <label>Trạng thái yêu cầu</label>
                    <select name="status" class="form-control">
                        <optgroup label="Có thể chọn">
                            @foreach($allowedStatuses as $key)
                            <option value="{{ $key }}">{{ $statusLabels[$key] }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Không thể chọn">
                            @foreach(array_diff($allStatuses, $allowedStatuses) as $key)
                            <option value="{{ $key }}" disabled
                                {{ $currentStatus == $key ? 'selected' : '' }}>
                                {{ $statusLabels[$key] }}
                            </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                {{-- Trạng thái bank --}}
                <div class="form-group">
                    <label>Trạng thái TK ngân hàng</label>
                    <select name="bank_account_status" class="form-control">
                        <optgroup label="Có thể chọn">
                            @foreach($allowedBanks as $key)
                            <option value="{{ $key }}">{{ $bankLabels[$key] }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Không thể chọn">
                            @foreach(array_diff($allBanks, $allowedBanks) as $key)
                            <option value="{{ $key }}" disabled
                                {{ $currentBank == $key ? 'selected' : '' }}>
                                {{ $bankLabels[$key] }}
                            </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ghi chú từ Admin</label>
                    <textarea name="admin_reason" class="form-control" rows="3">{{ old('admin_reason', $refund->admin_reason) }}</textarea>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" name="is_send_money" class="form-check-input" id="is_send_money" value="1" {{ $refund->is_send_money ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_send_money">Đã chuyển tiền</label>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
