<!doctype html>
<html lang="en">
  <head>
    <title>Đổi mật khẩu</title>
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
          <div class="col-md-6 text-center mb-5">
            <h2 class="heading-section">Đổi mật khẩu</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-4">
            <div class="login-wrap p-0">

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

    <script src="{{ asset('login-form-20/js/jquery.min.js') }}"></script>
    <script src="{{ asset('login-form-20/js/popper.js') }}"></script>
    <script src="{{ asset('login-form-20/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('login-form-20/js/main.js') }}"></script>

  </body>
</html>
