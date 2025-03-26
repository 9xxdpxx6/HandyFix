@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список категорий</h3>
            @can('create', \App\Models\Category::class)
            <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary btn-sm">Добавить категорию</a>
            @endcan
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.categories.index') }}" class="row g-3 mb-3">
                <!-- Количество элементов на странице -->
                <div class="col-md-2">
                    <label for="limit" class="form-label visually-hidden">Отображать по</label>
                    <select name="limit" id="limit" class="form-select">
                        <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>Отображать по 25</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>Отображать по 50</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>Отображать по 100</option>
                    </select>
                </div>

                <!-- Сортировка -->
                <div class="col-md-2">
                    <label for="sort" class="form-label visually-hidden">Сортировка</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>По умолчанию</option>
                        <option value="alphabet_asc" {{ request('sort') == 'alphabet_asc' ? 'selected' : '' }}>А-Я</option>
                        <option value="alphabet_desc" {{ request('sort') == 'alphabet_desc' ? 'selected' : '' }}>Я-А</option>
                    </select>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-4 ms-auto">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск по названию или описанию..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица категорий -->
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th>Иконка</th>
                    <th>Описание</th>
                    <th class="text-end">Действия</th>
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
                                <div class="display-2 d-flex justify-content-center">
                                    <div class="bg-light rounded-2 p-1 icon-square">
                                        <i class="hf-icon {{ $category->icon }}"></i>
                                    </div>
                                </div>
                            @else
                                <div class="display-2 d-flex justify-content-center">
                                    <div class="bg-light rounded-2 p-1 icon-square">
                                        <i class="hf-icon hf-no-image"></i>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>{{ $category->description }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.categories.show', $category) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20" />
                            </a>
                            
                            @can('update', $category)
                            <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20" />
                            </a>
                            @endcan
                            
                            @can('delete', $category)
                            <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                    <x-icon icon="trash-can" class="icon-20"/>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>

                    <!-- Дочерние категории -->
                    @if ($category->children->isNotEmpty())
                        <tr id="children-{{ $category->id }}" class="children-row" style="display: none; opacity: 0;">
                            @foreach ($category->children as $child)
                                <td>{{ $child->id }}</td>
                                <td>{{ $child->name }}</td>
                                <td class="text-center">
                                    @if($child->icon)
                                        <div class="display-2 d-flex justify-content-center">
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
                                    <a href="{{ route('dashboard.categories.show', $child) }}" class="btn btn-sm btn-outline-info">
                                        <x-icon icon="eye" class="icon-20" />
                                    </a>
                                    <a href="{{ route('dashboard.categories.edit', $child) }}" class="btn btn-sm btn-outline-warning">
                                        <x-icon icon="pencil-square" class="icon-20" />
                                    </a>
                                    <form action="{{ route('dashboard.categories.destroy', $child) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                            <x-icon icon="trash-can" class="icon-20"/>
                                        </button>
                                    </form>
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>
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
