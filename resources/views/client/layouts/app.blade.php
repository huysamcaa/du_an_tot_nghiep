<!-- resources/views/layouts/master.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Trang chủ')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/Client/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/lightcase.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/settings.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/ulina-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/ignore_for_wp.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/preset.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/Client/css/responsive.css') }}">
    {{-- ... Thêm các file css cần thiết tương tự ... --}}
</head>
<body>
    @include('client.layouts.header')

    @yield('content')

    @include('client.layouts.footer')

    {{-- JS --}}
   

        <script src="{{ asset('assets/Client/js/jquery.js') }}"></script>
        <script src="{{ asset('assets/Client/js/jquery-ui.js') }}"></script>
        <script src="{{ asset('assets/Client/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/shuffle.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/owl.carousel.filter.js') }}"></script>
        <script src="{{ asset('assets/Client/js/jquery.appear.js') }}"></script>
        <script src="{{ asset('assets/Client/js/lightcase.js') }}"></script>
        <script src="{{ asset('assets/Client/js/jquery.nice-select.js') }}"></script>
        <script src="{{ asset('assets/Client/js/slick.js') }}"></script>
        <script src="{{ asset('assets/Client/js/jquery.plugin.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/jquery.countdown.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/circle-progress.js') }}"></script>

        <script src="{{ asset('assets/Client/js/gmaps.js') }}"></script>
        <!-- <script src="https://maps.google.com/maps/api/js?key=AIzaSyCA_EDGVQleQtHIp2fZ-V56QFRbRL8cXT8"></script> -->

        <script src="{{ asset('assets/Client/js/jquery.themepunch.tools.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/jquery.themepunch.revolution.min.js') }}"></script>

        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.actions.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.migration.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.navigation.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.parallax.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
        <script src="{{ asset('assets/Client/js/extensions/revolution.extension.video.min.js') }}"></script>

        <script src="{{ asset('assets/Client/js/theme.js') }}"></script>
    {{-- ... Thêm các file js cần thiết tương tự ... --}}
</body>
</html>
