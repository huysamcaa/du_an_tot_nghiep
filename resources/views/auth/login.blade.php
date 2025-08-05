@extends('layouts.auth')

@section('title', 'Đăng nhập')
@section('page-title', 'Đăng nhập')

@section('content')
  <form action="{{ route('login') }}" method="POST" class="signin-form">
    @csrf

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="Email">
       @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="form-group">
      <input id="password-field" type="password" name="password" class="form-control" placeholder="Mật khẩu">
      @error('password') <small class="text-danger">{{ $message }}</small> @enderror
      <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
    </div>ư
    <div class="form-group">
      <button type="submit" class="form-control btn btn-primary submit px-3">Đăng nhập</button>
    </div>
    <div class="form-group d-md-flex">
      <div class="w-50">
        <label class="checkbox-wrap checkbox-primary">Ghi nhớ đăng nhập
          <input type="checkbox" name="remember">
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="w-50 text-md-right">
        <a href="{{ route('password.request') }}" style="color: #fff">Quên mật khẩu?</a>
      </div>
    </div>
  </form>

  <p class="w-100 text-center">Bạn chưa có tài khoản? <a href="{{ route('register') }}" style="color: #ffc107">Đăng ký</a></p>
@endsection
