@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="container">
        <h1>{{ $icon['name'] }}</h1>

        <!-- Ключевые слова -->
        <p><strong>Ключевые слова:</strong> {{ $icon['keywords'] }}</p>

        <!-- Разделение на две колонки -->
        <div class="row">
            <!-- Левая колонка: SVG-код -->
            <div class="col-md-6">
                <pre><code class="language-html">{{ $icon['svg'] }}</code></pre>
            </div>

            <!-- Правая колонка: Отображение иконки -->
            <div class="col-md-6 text-center">
                <x-icon icon="{{ $icon['name'] }}" class="icon-350 me-2"/>
            </div>
        </div>

        <!-- Кнопки действий -->
        <div class="mt-3">
            <a href="{{ route('dashboard.icons.index') }}" class="btn btn-secondary">Назад к списку</a>
            <a href="{{ route('dashboard.icons.edit', $icon['name']) }}" class="btn btn-warning">Редактировать</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
    <script>
        // Функция для форматирования SVG-кода
        function formatSvgCode(svgCode) {
            let formattedCode = svgCode.trim();
            formattedCode = formattedCode.replace(/</g, '\n<');
            formattedCode = formattedCode.replace(/>/g, '>\n');
            formattedCode = formattedCode.split('\n').filter(line => line.trim() !== '').join('\n');
            return formattedCode;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const svgElements = document.querySelectorAll('.language-html');
            svgElements.forEach(element => {
                const originalCode = element.textContent;
                const formattedCode = formatSvgCode(originalCode);
                element.textContent = formattedCode;
                Prism.highlightElement(element); // Применяем Prism.js к элементу
            });
        });
    </script>
@endpush
