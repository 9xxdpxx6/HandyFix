@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Типы услуг</h3>
            @can('create', \App\Models\ServiceType::class)
            <a href="{{ route('dashboard.service-types.create') }}" class="btn btn-primary btn-sm">Добавить тип услуги</a>
            @endcan
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.service-types.index') }}" class="row g-3 mb-3">
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
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица типов услуг -->
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
                    <th>Описание</th>
                    <th>Количество услуг</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($serviceTypes as $serviceType)
                    <tr>
                        <td>{{ $serviceType->id }}</td>
                        <td>
                            @if ($serviceType->icon)
                                <x-icon icon="{{ $serviceType->icon }}" class="icon-20 me-1"/>
                            @endif
                            {{ $serviceType->name }}
                        </td>
                        <td>{{ Str::limit($serviceType->description, 100) ?: 'Нет описания' }}</td>
                        <td>{{ $serviceType->services_count }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('dashboard.service-types.show', $serviceType) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            
                            @can('update', $serviceType)
                            <a href="{{ route('dashboard.service-types.edit', $serviceType) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            @endcan
                            
                            @can('delete', $serviceType)
                            <form action="{{ route('dashboard.service-types.destroy', $serviceType) }}" method="POST" style="display:inline;">
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

            <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $serviceTypes->links() }}
            </div>
        </div>
    </div>
@endsection 