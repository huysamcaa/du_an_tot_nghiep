<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; margin: 0; }
        .login-container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold;}
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .remember-me { display: flex; align-items: center; margin-bottom: 15px; }
        .remember-me input { margin-right: 8px; }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover { background-color: #0056b3; }
        .text-center { text-align: center; margin-top: 15px; }
        .text-center a { color: #007bff; text-decoration: none; }
        .text-center a:hover { text-decoration: underline; }
        .alert-success { color: green; background-color: #d4edda; border-color: #c3e6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-danger { color: red; background-color: #f8d7da; border-color: #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập tài khoản</h2>

        {{-- Hiển thị thông báo thành công (ví dụ: sau khi đăng ký) --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Hiển thị thông báo lỗi (ví dụ: sau khi đăng nhập thất bại) --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf {{-- CSRF token cho bảo mật --}}

            <div class="form-group">
                <label for="email">Địa chỉ Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ghi nhớ đăng nhập</label>
            </div>

            <button type="submit">Đăng nhập</button>
        </form>

        <div class="text-center">
            <a href="#">Quên mật khẩu?</a><br>
            Bạn chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
        </div>
    </div>
</body>
</html>
