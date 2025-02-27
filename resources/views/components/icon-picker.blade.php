@section('styles')
    <style>
        .icon-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, 50px);
            gap: 10px;
            overflow-y: auto;
            border: 1px solid #ccc;
            opacity: 0;
            max-height: 0;
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        }

        .icon-list.show {
            opacity: 1;
            max-height: 300px;
        }

        .icon-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            cursor: pointer;
        }

        .icon-item svg {
            width: 100%;
            height: 100%;
        }

        .icon-item:hover {
            background-color: #757575;
        }
    </style>
@endsection

<div class="icon-picker">
    <label for="icon" class="form-label">Иконка</label>
    <div class="selected-icon" id="selected-icon">
        @if ($selectedIcon)
            <div class="display-2 mb-2">
                <div class="bg-light rounded-2 p-1 icon-square">
                    <i class="hf-icon {{ $selectedIcon }}"></i>
                </div>
            </div>
        @else
            <span>Иконка не выбрана</span>
        @endif
    </div>
    <input type="hidden" name="icon" id="icon-input" value="{{ $selectedIcon }}">
    <input type="text" class="form-control" id="icon-search" placeholder="Поиск..." autocomplete="off"/>
    <div class="icon-list rounded-2 p-2 mt-2" id="icon-list">
        @foreach ($icons as $name => $icon)
            <div class="icon-item rounded-3 p-1" data-name="{{ $name }}" data-keywords="{{ implode(' ', $icon['keywords'] ?? []) }}" data-svg="{{ htmlspecialchars($icon['svg']) }}">
                {!! htmlspecialchars_decode($icon['svg']) !!}
            </div>
        @endforeach
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('icon-search');
            const iconList = document.getElementById('icon-list');
            const iconItems = document.querySelectorAll('.icon-item');
            const selectedIconContainer = document.getElementById('selected-icon');
            const iconInput = document.getElementById('icon-input');

            // Показать список при фокусе на поле поиска
            searchInput.addEventListener('focus', () => {
                iconList.classList.add('show');
            });

            // Скрыть список при потере фокуса
            searchInput.addEventListener('blur', () => {
                setTimeout(() => {
                    iconList.classList.remove('show');
                }, 50);
            });

            // Фильтрация иконок по вводу (по ключевым словам)
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                iconItems.forEach(item => {
                    const keywords = item.getAttribute('data-keywords').toLowerCase();
                    if (keywords.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Выбор иконки
            iconItems.forEach(item => {
                item.addEventListener('click', () => {
                    const iconName = item.getAttribute('data-name');

                    // Обновляем отображение выбранной иконки
                    selectedIconContainer.innerHTML = `
                    <div class="display-2 mb-2">
                        <div class="bg-light rounded-2 p-1 icon-square">
                            <i class="hf-icon hf-${iconName}"></i>
                        </div>
                    </div>
                `;

                    // Обновляем скрытое поле ввода
                    iconInput.value = `hf-${iconName}`;

                    // Скрываем выпадающий список
                    iconList.classList.remove('show');

                    // Очищаем поле поиска
                    searchInput.value = '';
                });
            });
        });
    </script>
@endsection
