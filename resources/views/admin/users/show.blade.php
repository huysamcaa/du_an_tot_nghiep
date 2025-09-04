@extends('admin.layouts.app')

@section('title','Chi tiết tài khoản')

@section('content')
<div class="content">
    {{-- HEADER giống trang sản phẩm --}}
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết tài khoản</h4>
            <h6>Thông tin chi tiết và thống kê về tài khoản</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fa fa-arrow-left me-1"></i> Quay lại
            </a>
            @if($user->status === 'active')
                <form action="{{ route('admin.users.lock', $user->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Khóa tài khoản này?')">
                    @csrf @method('PATCH')
                    <button class="btn btn-warning ms-2">
                        <i class="fa fa-lock me-1"></i> Khóa
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- BODY --}}
    <div class="card">
        <div class="card-body">
            <div class="row">
                {{-- TRÁI: Thông tin cơ bản (mô phỏng “Thông tin cơ bản” của sản phẩm) --}}
                <div class="col-lg-8">
                    <div class="card p-4">
                        <div class="row g-4">
                            {{-- Ảnh đại diện --}}
                            <div class="col-lg-5 col-md-12">
                                <div class="mb-3">
                                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/images/default.png') }}"
                                         alt="Avatar" class="img-fluid rounded shadow-sm"
                                         style="max-height: 260px; object-fit: cover;">
                                </div>
                            </div>

                            {{-- Bảng thông tin --}}
                            <div class="col-lg-7 col-md-12">
                                <h3 class="fw-bold text-primary">{{ $user->name }}</h3>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-muted">ID</th>
                                                <td><span class="badge bg-light text-dark">#{{ $user->id }}</span></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Email</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Số điện thoại</th>
                                                <td>{{ $user->phone_number ?? '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Giới tính</th>
                                                <td>{{ $user->gender ? ucfirst($user->gender) : '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Ngày sinh</th>
                                                <td>{{ optional($user->birthday)->format('d/m/Y') ?? '—' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Vai trò</th>
                                                <td>
                                                    @if($user->role)
                                                        <span class="badge bg-info text-dark">{{ ucfirst($user->role) }}</span>
                                                    @else — @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Nhóm</th>
                                                <td>
                                                    @if($user->user_group)
                                                        <span class="badge bg-secondary">{{ ucfirst($user->user_group) }}</span>
                                                    @else — @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Trạng thái</th>
                                                <td>
                                                    @if($user->status === 'active')
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge bg-danger">Bị khóa</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted">Ngày tạo</th>
                                                <td>{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- MÔ TẢ (nếu muốn có phần giống “mô tả” ở sản phẩm, có thể bỏ) --}}
                        {{-- <hr class="my-4">
                        <div>
                            <h6 class="text-muted mb-2">Ghi chú</h6>
                            <p class="fst-italic text-muted">—</p>
                        </div> --}}
                    </div>
                </div>

                {{-- PHẢI: Thống kê & Đơn hàng gần đây (mô phỏng box phải của sản phẩm) --}}
               <div class="col-lg-4">
    <div class="card mb-4 shadow-sm sticky-top" style="top: 100px;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-history me-2"></i>Đơn hàng gần đây</h5>
        </div>
        <div class="card-body">
            @if(empty($recentOrders) || $recentOrders->isEmpty())
                <div class="alert alert-info mb-0 text-center">
                    Chưa có đơn hàng nào cho người dùng này
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentOrders as $o)
                            @php
                                $isPaid  = (bool)($o->is_paid ?? false);
                                $isRf    = (bool)($o->is_refund ?? false);
                                $statusText  = $isRf ? 'Đã hoàn' : ($isPaid ? 'Đã thanh toán' : 'Chưa thanh toán');
                                $statusClass = $isRf ? 'bg-danger' : ($isPaid ? 'bg-success' : 'bg-secondary');
                            @endphp
                            <tr>
                                <td>
                                    @if(Route::has('admin.orders.show'))
                                        <a href="{{ route('admin.orders.show', $o->id) }}" class="text-primary">
                                            #{{ $o->code ?? $o->id }}
                                        </a>
                                    @else
                                        #{{ $o->code ?? $o->id }}
                                    @endif
                                </td>
                                <td>{{ optional($o->created_at)->format('d/m/Y H:i') }}</td>
                                <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

            </div>
        </div>
    </div>
</div>
@endsection
