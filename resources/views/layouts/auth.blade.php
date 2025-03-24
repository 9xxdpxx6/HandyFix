<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'HandyFix'))</title>
    <link href="{{ asset('dashboard/css/coreui.css') }}" rel="stylesheet">
    <link href="{{ asset('icons/css/icons.css') }}" rel="stylesheet">
    @stack('styles')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-dark">
    <div class="container">
        @yield('content')
    </div>
    
    <script src="{{ asset('dashboard/js/color-modes.js') }}"></script>
    <script src="{{ asset('dashboard/js/coreui.bundle.js') }}"></script>
    @stack('scripts')
</body>
</html> 