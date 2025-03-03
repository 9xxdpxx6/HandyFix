@extends('layouts.app')

@section('content')
    <div>
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
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        <div>
                            {{ $category->name }}
                        </div>
                        @if ($category->children->isNotEmpty())
                            <button class="btn btn-sm btn-outline-secondary toggle-children" data-target="children-{{ $category->id }}">
                                Показать подкатегории
                            </button>
                        @endif
                    </td>
                    <td>
                        @if($category->icon)
                            <div class="display-2">
                                <div class="bg-light rounded-2 p-1 icon-square">
                                    <i class="hf-icon {{ $category->icon }}"></i>
                                </div>
                            </div>
                        @else
                            <div class="display-2">
                                <div class="bg-light rounded-2 p-1 icon-square">
                                    <i class="hf-icon hf-no-image"></i>
                                </div>
                            </div>
                        @endif
                    </td>
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
                    <tr id="children-{{ $category->id }}" class="children-row" style="display: none; opacity: 0;">
                        @foreach ($category->children as $child)
                            <td>{{ $child->id }}</td>
                            <td>{{ $child->name }}</td>
                            <td>
                                @if($child->icon)
                                    <div class="display-2">
                                        <div class="bg-light rounded-2 p-1 icon-square">
                                            <i class="hf-icon {{ $child->icon }}"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="display-2">
                                        <div class="bg-light rounded-2 p-1 icon-square">
                                            <i class="hf-icon hf-no-image"></i>
                                        </div>
                                    </div>
                                @endif
                            </td>
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
                        @endforeach
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>

    <style>
        .children-row {
            transition: opacity 0.2s ease;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-children');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const childrenRow = document.getElementById(targetId);

                    if (childrenRow.style.display === 'none') {
                        childrenRow.style.display = 'table-row';

                        setTimeout(() => {
                            childrenRow.style.opacity = '1';
                        }, 50);

                        this.textContent = 'Скрыть подкатегории';
                    } else {
                        childrenRow.style.opacity = '0';

                        setTimeout(() => {
                            childrenRow.style.display = 'none';
                        }, 200);

                        this.textContent = 'Показать подкатегории';
                    }
                });
            });
        });
    </script>
@endpush
