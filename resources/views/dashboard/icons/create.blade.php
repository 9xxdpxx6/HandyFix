@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="container">
        <h1>Добавить иконку</h1>
        <form id="iconForm" action="{{ route('dashboard.icons.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Левая колонка: Редактор SVG-кода -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Название</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="svg" class="form-label">SVG-код</label>
                        <textarea name="svg" id="svg" class="form-control" rows="15" required></textarea>
                        <pre><code class="language-html" id="formattedSvg"></code></pre>
                    </div>
                    <div class="mb-3">
                        <label for="keywords" class="form-label">Ключевые слова (через запятую)</label>
                        <input type="text" name="keywords" id="keywords" class="form-control">
                    </div>
                </div>

                <!-- Правая колонка: Отображение иконки -->
                <div class="col-md-6 text-center">
                    <div id="iconPreview" class="mt-3">
                        <!-- Предварительный просмотр будет здесь -->
                    </div>
                </div>
            </div>
            <div class="instruction">
                <h3>Инструкция по обработке SVG-кода:</h3>
                <ol>
                    <li><strong>Установка fill и stroke на currentColor:</strong>
                        <ul>
                            <li>Найдите все атрибуты fill и stroke в SVG-коде.</li>
                            <li>Убедитесь, что их значения установлены на currentColor. Если они отсутствуют или имеют другие значения, замените их на currentColor.</li>
                        </ul>
                    </li>
                    <li><strong>Удаление ширины и высоты:</strong>
                        <ul>
                            <li>Найдите атрибуты width и height в корневом элементе &lt;svg&gt;.</li>
                            <li>Удалите эти атрибуты, чтобы SVG-код не содержал явно заданных размеров.</li>
                        </ul>
                    </li>
                    <li><strong>Удаление лишних пробелов и переносов строк:</strong>
                        <ul>
                            <li>Удалите все лишние пробелы, табуляции и переносы строк из SVG-кода.</li>
                            <li>Оставьте только необходимые пробелы между атрибутами и тегами.</li>
                        </ul>
                    </li>
                </ol>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
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
            const svgTextarea = document.getElementById('svg');
            const iconPreview = document.getElementById('iconPreview');
            const formattedSvgElement = document.querySelector('#formattedSvg');

            svgTextarea.addEventListener('input', function () {
                const svgCode = svgTextarea.value;
                iconPreview.innerHTML = svgCode;

                if (formattedSvgElement) {
                    const formattedCode = formatSvgCode(svgCode);
                    formattedSvgElement.textContent = formattedCode;
                    Prism.highlightElement(formattedSvgElement); // Применяем Prism.js к элементу
                }
            });
        });
    </script>
@endpush
