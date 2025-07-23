@extends('layouts.auth')

@section('title', 'Đăng nhập hệ thống bán áo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow rounded-4 p-4">
                <h3 class="text-center mb-4">🧥 Đăng nhập tài khoản</h3>

                {{-- Hiển thị thông báo --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Địa chỉ email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="abc@example.com" value="{{ old('email') }}" autofocus>
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
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
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Đăng nhập
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small>Chưa có tài khoản?
                        <a href="{{ route('register') }}">Đăng ký ngay</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
