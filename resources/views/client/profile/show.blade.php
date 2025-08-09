@extends('client.layouts.app')

@section('content')
<style>
    .pageBannerSection {
        background:#ECF5F4;
        padding: 10px 0;
    }
    .pageBannerContent h2 {
        
        font-size: 72px;
        color:#52586D;
        font-family: 'Jost', sans-serif;
    }
    .pageBannerPath a {
        color: #007bff;
        text-decoration: none;
    }
    .checkoutPage {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
</style>
    <div class="checkoutPage">
        <!-- Banner nhỏ gọn -->
        <section class="pageBannerSection py-3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2 >Hồ sơ của tôi</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}" >Trang chủ</a>
                                &nbsp;&nbsp;&gt;&nbsp;&nbsp;
                                <span class="text-muted">Hồ sơ của tôi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Thông tin người dùng -->
        <div class="container py-5">
            <h4 class="mb-1 fw-bold">Hồ Sơ Của Tôi</h4>
            <p class="text-muted mb-4">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow border-0 rounded-4 p-4">
                <div class="row align-items-center">
                    <!-- Avatar -->
                    <div class="col-md-4 text-center mb-3 mb-md-0">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle img-fluid shadow"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center shadow"
                                style="width: 120px; height: 120px;">
                                <span class="text-muted">No Avatar</span>
                            </div>
                        @endif
                    </div>

                    <!-- Thông tin -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Họ tên:</label>
                                <div>{{ $user->name }}</div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Email:</label>
                                <div>{{ $user->email }}</div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Số điện thoại:</label>
                                <div>{{ $user->phone_number }}</div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Giới tính:</label>
                                <div>
                                    @if ($user->gender === 'male')
                                        Nam
                                    @elseif($user->gender === 'female')
                                        Nữ
                                    @else
                                        Không xác định
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Ngày sinh:</label>
                                <div>{{ $user->birthday ? $user->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <a href="{{ route('client.password.change.form') }}" class="btn btn-outline-danger btn-sm">
                                    <i class="fa-solid fa-key me-1"></i> Đổi mật khẩu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút chỉnh sửa -->
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('client.profile.edit') }}" class="ulinaBTN d-flex align-items-center px-3 py-2 btn-sm">
                        <i class="fa-solid fa-pen-to-square me-1"></i>
                        <span>Chỉnh sửa</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
