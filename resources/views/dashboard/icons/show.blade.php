@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация об иконке: {{ $icon['name'] }}</h5>
                <div>
                    @can('update', $icon['name'])
                    <a href="{{ route('dashboard.icons.edit', $icon['name']) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $icon['name'])
                    <form action="{{ route('dashboard.icons.destroy', $icon['name']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.icons.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            
            <div class="card-body">
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
            </div>
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
