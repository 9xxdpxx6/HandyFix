@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список категорий</h1>
        <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary mb-3">Добавить новую категорию</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Иконка</th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <!-- Родительская категория -->
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        {{ $category->name }}
                        @if ($category->children->isNotEmpty())
                            <!-- Кнопка для раскрытия/скрытия дочерних категорий -->
                            <button class="btn btn-sm btn-secondary toggle-children" data-target="#children-{{ $category->id }}">
                                Показать подкатегории
                            </button>
                        @endif
                    </td>
                    <td><img src="{{ $category->icon }}" alt="{{ $category->name }}" style="max-width: 50px;"></td>
                    <td>{{ $category->description }}</td>
                    <td class="text-end">
                        <a href="{{ route('dashboard.categories.show', $category) }}" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>

                <!-- Дочерние категории -->
                @if ($category->children->isNotEmpty())
                    <tr id="children-{{ $category->id }}" class="children-row" style="display: none;">
                        <td colspan="5" class="ps-5">
                            <table class="table table-borderless">
                                <tbody>
                                @foreach ($category->children as $child)
                                    <tr>
                                        <td>{{ $child->id }}</td>
                                        <td>{{ $child->name }}</td>
                                        <td><img src="{{ $child->icon }}" alt="{{ $child->name }}" style="max-width: 50px;"></td>
                                        <td>{{ $child->description }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('dashboard.categories.show', $child) }}" class="btn btn-sm btn-info">Просмотр</a>
                                            <a href="{{ route('dashboard.categories.edit', $child) }}" class="btn btn-sm btn-warning">Редактировать</a>
                                            <form action="{{ route('dashboard.categories.destroy', $child) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Обработчик для кнопки раскрытия/скрытия дочерних категорий
            const toggleButtons = document.querySelectorAll('.toggle-children');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const childrenRow = document.querySelector(`${targetId}`);
                    if (childrenRow.style.display === 'none') {
                        childrenRow.style.display = 'table-row';
                        this.textContent = 'Скрыть подкатегории';
                    } else {
                        childrenRow.style.display = 'none';
                        this.textContent = 'Показать подкатегории';
                    }
                });
            });
        });
    </script>
@endsection
