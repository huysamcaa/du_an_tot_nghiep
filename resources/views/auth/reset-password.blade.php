@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu')
@section('page-title', 'Đặt lại mật khẩu')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email"
                value="{{ old('email', request()->email) }}" autofocus>
        </div>

        <div class="form-group">
            <div class="position-relative">
                <input type="password" name="password" id="password-field" class="form-control" placeholder="Mật khẩu mới">
                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            </div>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input type="password" name="password_confirmation" id="password-field" class="form-control"
                placeholder="Xác nhận mật khẩu">
            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        </div>

        <div class="form-group">
            <button type="submit" class="form-control btn btn-primary submit px-3">Đặt lại mật khẩu</button>
        </div>
    </form>
@endsection
