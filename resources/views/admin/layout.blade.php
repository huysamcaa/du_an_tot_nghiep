<!DOCTYPE html>
<html>
<head>
    <title>Admin - @yield('title')</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div class="container mt-4">
        <h1>@yield('title')</h1>
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>
