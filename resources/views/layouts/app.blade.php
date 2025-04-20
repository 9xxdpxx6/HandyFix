<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'HandyFix') }}</title>
{{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
    <link href="{{ asset('dashboard/css/coreui.css') }}" rel="stylesheet">
    <link href="{{ asset('icons/css/icons.css') }}" rel="stylesheet">
    @stack('styles')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
@include('layouts.sidebar')
<div class="wrapper d-flex flex-column" style="height: 100vh" data-simplebar>
    @include('layouts.header')
    <div class="body flex-grow-1 min-vh-100">
        <div class="container-lg px-4">
            <div class="d-flex align-items-center mb-3">
                <div class="fs-2 fw-semibold">
                    @php
                        $breadcrumbComponent = new \App\View\Components\Breadcrumb();
                        $breadcrumbItems = $breadcrumbComponent->items;
                        $pageTitle = end($breadcrumbItems)['name'] ?? 'Панель управления';
                        
                        if ($pageTitle === 'Home') {
                            $pageTitle = 'Главная';
                        }
                    @endphp
                    {{ $pageTitle }}
                </div>
            </div>
            <x-breadcrumb />
            <br>
            @yield('content')
        </div>
    </div>
    <footer class="footer px-4 mt-5">
        <div><a href="{{ route('home') }}">{{ config('app.name', 'HandyFix') }} </a><a href="{{ route('dashboard.home') }}">Панель управления</a> © {{ now()->year }} PiedPiper.</div>
        <div class="ms-auto">Powered by&nbsp;<a href="{{ route('home') }}">{{ config('app.name', 'HandyFix') }} UI Components</a></div>
    </footer>
</div>
<script>
    const header = document.querySelector('header.header');

    document.addEventListener('scroll', () => {
        if (header) {
            header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
        }
    });
</script>
<!-- Plugins and scripts required by this view-->
<!-- <script src="{{ asset('dashboard/js/color-modes.js') }}"></script> -->
<script src="{{ asset('dashboard/js/coreui.bundle.js') }}"></script>
{{--<script src="vendors/chart.js/js/chart.umd.js"></script>--}}
{{--<script src="vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>--}}
@stack('scripts')
</body>
</html>
