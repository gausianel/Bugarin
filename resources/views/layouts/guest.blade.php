<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bugarin')</title>

    {{-- Tailwind CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    {{-- AOS Animation --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Custom Styles --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Content --}}
    <main class="flex-grow flex items-center justify-center">
        @yield('content')
    </main>

    {{-- JS Libraries --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>

    @stack('scripts')
</body>
</html>
