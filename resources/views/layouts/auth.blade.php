<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Đăng nhập')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ Bootstrap CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh">

    @yield('content')

    {{-- ✅ Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupToggle(inputId, iconWrapperId) {
                const input = document.getElementById(inputId);
                const iconWrapper = document.getElementById(iconWrapperId);
                const icon = iconWrapper.querySelector('i'); // lấy icon thực sự

                iconWrapper.addEventListener('click', function () {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                });
            }

            setupToggle('password', 'togglePassword');
            setupToggle('password_confirmation', 'togglePasswordConfirm');
        });
    </script>




</body>
</html>
