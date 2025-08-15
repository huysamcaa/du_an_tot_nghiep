<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="shortcut icon" href="{{ asset('assets/admin/img/favicon.jpg') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS Files --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    {{-- Select2 CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
</head>

<body>

    {{-- Loader --}}
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="main-wrapper">
        {{-- Header --}}
        @include('admin.layouts.header')

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Nội dung --}}
        <div class="page-wrapper">
            @yield('content')
        </div>
         <!-- Footer -->
        @include('admin.layouts.footer')
        <!-- /.site-footer -->
    </div>

    {{-- JS Files --}}
    <script src="{{ asset('assets/admin/js/jquery-3.6.0.min.js') }}"></script>

    {{-- Select2 JS phải đặt sau jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="{{ asset('assets/admin/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('assets/admin/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/admin/plugins/owlcarousel/owl.carousel.min.js') }}"></script>
    @stack('scripts')

</body>

</html>
