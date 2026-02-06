<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سافر | @yield('title', 'رحلتك المثالية')</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .text-safer-blue {
            color: #2C67FF;
        }

        .bg-safer-blue {
            background-color: #2C67FF;
        }
    </style>

    @yield('styles')
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Content -->
    <main class="flex-grow py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    @yield('scripts')
</body>

</html>