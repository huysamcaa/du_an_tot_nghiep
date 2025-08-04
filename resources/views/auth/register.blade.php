<!doctype html>
<html lang="en">
  <head>
    <title>Đăng ký</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('login-form-20/css/style.css') }}">
  </head>
  <body class="img js-fullheight" style="background-image: url('{{ asset('login-form-20/images/cucu.png') }}');">

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 text-center mb-2">
            <h2 class="heading-section">Đăng ký tài khoản</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-5">
            <div class="login-wrap p-4">
              <form action="{{ route('register') }}" method="POST" class="signin-form">
                @csrf

                <div class="form-group">
                  <input type="text" name="name" class="form-control" placeholder="Tên của bạn" value="{{ old('name') }}" required>
                  @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                  @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                  <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                  @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu" required>
                </div>

                <div class="form-group">
                  <input type="tel" name="phone_number" class="form-control" placeholder="Số điện thoại (tùy chọn)" value="{{ old('phone_number') }}">
                  @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                  <button type="submit" class="form-control btn btn-primary submit px-3">Đăng ký</button>
                </div>

                <p class="w-100 text-center">Bạn đã có tài khoản? <a href="{{ route('login') }}" style="color: #ffc107">Đăng nhập ngay</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="{{ asset('login-form-20/js/jquery.min.js') }}"></script>
    <script src="{{ asset('login-form-20/js/popper.js') }}"></script>
    <script src="{{ asset('login-form-20/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('login-form-20/js/main.js') }}"></script>
  </body>
</html>
