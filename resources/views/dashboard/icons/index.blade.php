@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список иконок</h3>
            @can('create', 'Icon')
            <a href="{{ route('dashboard.icons.create') }}" class="btn btn-primary btn-sm">Добавить иконку</a>
            @endcan
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.icons.index') }}" class="row g-3 mb-3">
                <!-- Сортировка -->
                <div class="col-md-2">
                    <label for="sort" class="form-label visually-hidden">Сортировка</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>По умолчанию</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Название (А-Я)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Название (Я-А)</option>
                    </select>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-4 ms-auto">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск по названию или ключевым словам..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица иконок -->
        <div class="card-body pt-0">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th>Ключевые слова</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($icons as $name => $icon)
                    <tr>
                        <td>
                            <x-icon icon="{{ $icon['name'] }}" class="icon-50 me-2"/>
                        </td>
                        <td>{{ $name }}</td>
                        <td>{{ $icon['keywords'] }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.icons.show', $name) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            
                            @can('update', $name)
                            <a href="{{ route('dashboard.icons.edit', $name) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            @endcan
                            
                            @can('delete', $name)
                            <form action="{{ route('dashboard.icons.destroy', $name) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                    <x-icon icon="trash-can" class="icon-20"/>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация не нужна, так как все на одной странице -->
        </div>
    </div>
@endsection
