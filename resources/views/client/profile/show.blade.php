@extends('client.layouts.app')
@section('title','Chi tiết người dùng')
@section('content')
    <style>
        .pageBannerSection {
            background: #ECF5F4;
            padding: 10px 0;
        }

        .pageBannerContent h2 {

            font-size: 72px;
            color: #52586D;
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

        .pageBannerSection {
            padding: 20px 0;
            min-height: 10px;
        }

        .pageBannerSection .pageBannerContent h2 {
            font-size: 38px;
            margin-bottom: 10px;
        }

        .pageBannerPath {
            font-size: 14px;
        }
    </style>
    <!-- BEGIN: Page Banner Section -->
    <section class="pageBannerSection">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pageBannerContent text-center">
                        <h2>Thông Tin Cá Nhân</h2>
                        <div class="pageBannerPath">
                            <a href="{{ route('client.home') }}">Trang chủ</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span>Thông Tin Cá
                                Nhân</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Page Banner Section -->
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
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle img-fluid shadow"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" class="rounded-circle img-fluid shadow"
                                style="width: 120px; height: 120px; object-fit: cover;">
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
                                    @if ($user->gender === 'male')
                                        Nam
                                    @elseif($user->gender === 'female')
                                        Nữ
                                    @else
                                        Không xác định
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <label class="form-label fw-bold">Ngày sinh:</label>
                                <div>{{ $user->birthday ? $user->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <label class="form-label fw-bold">Hạng thành viên:</label>
                                <div>
                                    <span class="badge {{ $user->groupBadgeClass() }}">
                                        {{ $user->groupLabel() }}
                                    </span>
                                </div>

                                @php
                                    $spent = $user->totalSpent();
                                    $nextLabel = null;
                                    $need = 0;

                                    if ($spent < 3_000_000) {
                                        // < 3tr: Khách -> lên Thành viên
                                        $need = 3_000_000 - $spent;
                                        $nextLabel = 'Thành viên';
                                    } elseif ($spent < 4_000_000) {
                                        // [3tr, 4tr): Thành viên -> lên VIP
                                        $need = 4_000_000 - $spent;
                                        $nextLabel = 'VIP';
                                    }
                                @endphp

                                <small class="text-muted">
                                    Đã chi: {{ number_format($spent) }}đ
                                    @if ($nextLabel)
                                        – Còn thiếu {{ number_format($need) }}đ để lên hạng {{ $nextLabel }}
                                    @endif
                                </small>
                            </div>


                            <div class="col-sm-6 mb-2">
                                <a href="{{ route('client.password.change.form') }}" class="btn btn-outline-danger btn-sm">
                                    <i class="fa-solid fa-key me-1"></i> Đổi mật khẩu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('client.profile.edit') }}"
                        class="ulinaBTN d-flex align-items-center px-2 py-2 btn-sm">

                        <span class="mx-auto">Chỉnh sửa</span>
                    </a>
                </div>

            </div>
        @endsection
