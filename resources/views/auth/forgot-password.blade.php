@extends('layouts.auth')

@section('title', 'Quên mật khẩu')
@section('page-title', 'Quên mật khẩu')

@section('content')
  <form method="POST" action="{{ route('password.email') }}" class="signin-form">
    @csrf
    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="Nhập email của bạn">
    </div>
    <div class="form-group">
      <button type="submit" class="form-control btn btn-warning submit px-3">Gửi liên kết đặt lại mật khẩu</button>
    </div>
  </form>
@endsection
