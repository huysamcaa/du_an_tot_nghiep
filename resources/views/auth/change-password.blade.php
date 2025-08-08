@extends('layouts.auth')

@section('title', 'Đổi mật khẩu')
@section('page-title', 'Đổi mật khẩu')

@section('content')

              @if (session('status'))
                  <div class="alert alert-success">{{ session('status') }}</div>
              @endif

              <form action="{{ route('password.change') }}" method="POST" class="signin-form">
                @csrf

                <div class="form-group">
                  <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Mật khẩu hiện tại" >
                  @error('current_password')
                    <span class="text-danger small">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Mật khẩu mới" >
                  @error('new_password')
                    <span class="text-danger small">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <input type="password" name="new_password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu mới" >
                </div>

                <div class="form-group">
                  <button type="submit" class="form-control btn btn-primary submit px-3">Đổi mật khẩu</button>
                </div>

                <p class="w-100 text-center">
                  <a href="{{ route('client.home') }}" style="color: #ffc107">← Quay lại trang chủ</a>
                </p>
              </form>

            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
