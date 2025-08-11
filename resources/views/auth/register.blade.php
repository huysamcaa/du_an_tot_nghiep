@extends('layouts.auth')

@section('title', 'Đăng kí')
@section('page-title', 'Đăng kí tài khoản')

@section('content')
    <form action="{{ route('register') }}" method="POST" class="signin-form">
        @csrf

        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Tên của bạn" value="{{ old('name') }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input type="password" id="password-field" name="password" class="form-control" placeholder="Mật khẩu">
            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input type="password" id="password-field" name="password_confirmation" class="form-control"
                placeholder="Xác nhận mật khẩu">
            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        </div>

        <div class="form-group">
            <input type="tel" name="phone_number" class="form-control" placeholder="Số điện thoại (tùy chọn)"
                value="{{ old('phone_number') }}">
            @error('phone_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <button type="submit" class="form-control btn btn-primary submit px-3">Đăng ký</button>
        </div>
        <p class="w-100 text-center">Bạn đã có tài khoản? <a href="{{ route('login') }}" style="color: #ffc107">Đăng nhập
                ngay</a></p>
    </form>

@endsection
