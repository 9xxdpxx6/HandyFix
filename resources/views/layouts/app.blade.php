<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <div class="ms-auto">
            <a href="{{ route('dashboard.guide') }}" class="btn btn-sm btn-outline-primary me-2">
                <x-icon icon="list" class="icon-15 me-1"/> Справка
            </a>
            Powered by&nbsp;<a href="{{ route('home') }}">{{ config('app.name', 'HandyFix') }} UI Components</a>
        </div>
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
<script>
// Скрипт для экспорта статистики
(function() {
    if (window.statisticsExportInitialized) return;
    window.statisticsExportInitialized = true;
    
    // Используем делегирование событий для обработки кликов
    document.addEventListener('click', function(e) {
        // Проверяем, был ли клик на элементе с классом export-statistics-btn или его дочернем элементе
        const btn = e.target.closest('.export-statistics-btn');
        if (!btn) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        const url = btn.getAttribute('data-url');
        const format = btn.getAttribute('data-format');
        const startDate = btn.getAttribute('data-start-date') || '';
        const endDate = btn.getAttribute('data-end-date') || '';
        
        if (!url || !format) {
            console.error('Export: Missing URL or format', {url, format});
            alert('Ошибка: не указан URL или формат экспорта');
            return;
        }
        
        // Создаем форму для отправки
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';
        
        // CSRF токен
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        csrfInput.value = csrfToken ? csrfToken.getAttribute('content') : '';
        if (!csrfInput.value) {
            console.error('Export: CSRF token not found');
            return;
        }
        form.appendChild(csrfInput);
        
        // Формат
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = format;
        form.appendChild(formatInput);
        
        // Фильтры
        if (startDate) {
            const startInput = document.createElement('input');
            startInput.type = 'hidden';
            startInput.name = 'start_date';
            startInput.value = startDate;
            form.appendChild(startInput);
        }
        
        if (endDate) {
            const endInput = document.createElement('input');
            endInput.type = 'hidden';
            endInput.name = 'end_date';
            endInput.value = endDate;
            form.appendChild(endInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    });
})();
</script>
@stack('scripts')
</body>
</html>
