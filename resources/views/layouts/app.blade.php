{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bugarin')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100">

    <!-- Navigation Bar -->
    @include('partials.navbar')

    <main class="container mt-4">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
