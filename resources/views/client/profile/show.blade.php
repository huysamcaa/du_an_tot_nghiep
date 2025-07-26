@extends('client.layouts.app')

@section('content')
<div class="checkoutPage">
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2 class="display-4 ">Hồ sơ của tôi</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}" class="text-decoration-none text-dark">Trang chủ</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span class="text-muted">Hồ sơ của tôi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<div class="container py-5">
<div class="container py-5">
    <h4 class="mb-1 fw-bold">Hồ Sơ Của Tôi</h4>
    <p class="text-muted mb-4">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4">
        <div class="row align-items-center">
            <!-- Avatar -->
            <div class="col-md-4 text-center mb-3 mb-md-0">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle img-fluid shadow" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <span class="text-muted">No Avatar</span>
                    </div>
                @endif
            </div>

            <!-- Thông tin -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label fw-bold">Họ tên:</label>
                        <div>{{ $user->name }}</div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label fw-bold">Email:</label>
                        <div>{{ $user->email }}</div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label fw-bold">Số điện thoại:</label>
                        <div>{{ $user->phone_number }}</div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label fw-bold">Giới tính:</label>
                        <div>
                            @if($user->gender === 'male') Nam
                            @elseif($user->gender === 'female') Nữ
                            @else Không xác định
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label fw-bold">Ngày sinh:</label>
                        <div>{{ $user->birthday ? $user->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nút chỉnh sửa -->
        <div class="text-end mt-4">
            <a href="{{ route('client.profile.edit') }}" class="btn btn-outline-primary px-4">
                <i class="fa-solid fa-pen me-2"></i> Chỉnh sửa thông tin
            </a>
        </div>
    </div>
</div>
</div>
@endsection
