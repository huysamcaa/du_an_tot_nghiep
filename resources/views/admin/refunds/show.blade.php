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
                    <div class="col-md-4 mb-2"><strong>Tổng tiền:</strong> <div class="text-danger">{{ number_format($refund->total_amount, 0, ',', '.') }} đ</div></div>
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
                        <p><strong>Ảnh minh chứng:</strong><br>
                            <img src="{{ asset('storage/' . $refund->reason_image) }}" class="img-fluid rounded" style="max-width:160px;">
                        </p>
                        @endif
                        @if($refund->fail_reason)
                        <p><strong>Lý do lỗi (Admin):</strong> <span class="text-danger">{{ $refund->fail_reason }}</span></p>
                        @endif
                        @if($refund->img_fail_or_completed)
                        <p><strong>Ảnh trạng thái:</strong><br>
                            <img src="{{ asset('storage/' . $refund->img_fail_or_completed) }}" class="img-fluid rounded" style="max-width:160px;">
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
                                    @if(optional($item->product)->thumbnail)
                                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" style="width:36px;">
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td> @if($item->variant && $item->variant->attributeValues->count())
                                        {{ $item->variant->attributeValues->pluck('value')->implode(' - ') }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif</td>
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
                    $allowedStatuses = $statusTransitions[$refund->status] ?? [];
                    $allowedBanks = $bankTransitions[$refund->bank_account_status] ?? [];
                    $allStatuses = ['pending','receiving','completed','rejected','failed','cancel'];
                    $allBanks = ['unverified','verified','sent'];
                @endphp
                <form method="POST" action="{{ route('admin.refunds.update', $refund) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="status">Trạng thái yêu cầu</label>
                            <select name="status" id="status" class="form-control form-control-sm">
                                <optgroup label="Có thể chọn">
                                    @foreach($allowedStatuses as $key)
                                    <option value="{{ $key }}">{{ $statusLabels[$key] }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Không thể chọn">
                                    @foreach(array_diff($allStatuses, $allowedStatuses) as $key)
                                    <option value="{{ $key }}" disabled {{ $refund->status == $key ? 'selected' : '' }}>{{ $statusLabels[$key] }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bank_account_status">Trạng thái TK ngân hàng</label>
                            <select name="bank_account_status" id="bank_account_status" class="form-control form-control-sm">
                                <optgroup label="Có thể chọn">
                                    @foreach($allowedBanks as $key)
                                    <option value="{{ $key }}">{{ $bankLabels[$key] }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Không thể chọn">
                                    @foreach(array_diff($allBanks, $allowedBanks) as $key)
                                    <option value="{{ $key }}" disabled {{ $refund->bank_account_status == $key ? 'selected' : '' }}>{{ $bankLabels[$key] }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="admin_reason">Ghi chú từ Admin</label>
                        <textarea name="admin_reason" id="admin_reason" class="form-control form-control-sm" rows="2" placeholder="Nhập ghi chú...">{{ old('admin_reason', $refund->admin_reason) }}</textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="is_send_money" class="form-check-input" id="is_send_money" value="1" {{ $refund->is_send_money ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_send_money">Đã chuyển tiền</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"> <i class="fa fa-save"></i> Cập nhật</button>
                    <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Quay lại danh sách</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
