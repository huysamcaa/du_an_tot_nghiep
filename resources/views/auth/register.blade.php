@extends('layouts.auth')

@section('title', 'Đăng ký tài khoản')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4 p-4">
                    <h3 class="text-center mb-4">🧥 Đăng ký tài khoản</h3>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name">Tên của bạn:</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name') }}" autofocus>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email">Địa chỉ Email:</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" >
                                <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" >
                                <span class="input-group-text" id="togglePasswordConfirm" style="cursor: pointer;">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number">Số điện thoại (tùy chọn):</label>
                            <input type="tel" id="phone_number" name="phone_number" class="form-control"
                                value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gender">Giới tính (tùy chọn):</label>
                            <select id="gender" name="gender" class="form-select">
                                <option value="">Chọn giới tính</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="birthday">Ngày sinh (tùy chọn):</label>
                            <input type="date" id="birthday" name="birthday" class="form-control"
                                value="{{ old('birthday') }}">
                            @error('birthday')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                    </form>

                    <div class="text-center mt-3">
                        Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
