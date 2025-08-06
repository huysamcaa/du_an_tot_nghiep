<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác thực email</title>
</head>
<body>
    <h2>Xin chào {{ $user->name }}!</h2>
    <p>Cảm ơn bạn đã đăng ký tài khoản.</p>
    <p>Mã OTP để xác thực email của bạn là:</p>

    <h3 style="color:#2d89ef;letter-spacing:3px;">{{ $user->code_verified_email }}</h3>

    <p>Mã có hiệu lực trong 5 phút. Vui lòng không chia sẻ mã này với bất kỳ ai.</p>
</body>
</html>
