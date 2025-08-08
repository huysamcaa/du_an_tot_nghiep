<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xác thực Email</title>
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome CDN (nếu muốn icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f7f7f7;
        }

        .center-otp {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <div class="center-otp">
        <div class="card shadow" style="width: 350px;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h4 class="mb-1">Xác thực Email</h4>
                    <p class="text-muted mb-2" style="font-size: 15px;">
                        Nhập mã OTP đã gửi về email của bạn để xác thực tài khoản.
                    </p>
                </div>
                <form action="{{ route('verification.otp.verify') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <div class="form-group mb-3">
                        <label for="otp" class="font-weight-bold">Mã OTP</label>
                        <input type="text" name="otp" id="otp"
                            class="form-control text-center {{ $errors->has('otp') ? 'is-invalid' : '' }}"
                            maxlength="6" autofocus placeholder="Nhập mã 6 số">
                        @error('otp')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-3">Xác thực</button>
                </form>
                <div class="text-center mt-3">

                </div>
                <form method="POST" action="{{ route('otp.resend') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <small class="text-muted">Không nhận được mã?  <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Gửi lại mã</button></small>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
