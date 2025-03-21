@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список услуг</h3>
            <a href="{{ route('dashboard.services.create') }}" class="btn btn-primary btn-sm">Добавить услугу</a>
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.services.index') }}" class="row g-3 mb-3">
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
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена (от низкой)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена (от высокой)</option>
                    </select>
                </div>

                <!-- Фильтр по цене -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" name="price_min" id="price_min" class="form-control" placeholder="Цена от"
                            value="{{ request('price_min') }}">
                        <input type="number" name="price_max" id="price_max" class="form-control" placeholder="Цена до"
                            value="{{ request('price_max') }}">
                    </div>
                </div>

                <!-- Фильтр по типу -->
                <div class="col-md-2">
                    <label for="service_type_id" class="form-label visually-hidden">Тип услуги</label>
                    <select name="service_type_id" id="service_type_id" class="form-select">
                        <option value="">Все типы</option>
                        @foreach ($serviceTypes as $id => $name)
                            <option value="{{ $id }}" {{ request('service_type_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-20"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица услуг -->
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
                    <th>Тип</th>
                    <th>Цена</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->serviceType->name }}</td>
                        <td>{{ number_format($service->price, 2, '.', ' ') }} ₽</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('dashboard.services.show', $service) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            <a href="{{ route('dashboard.services.edit', $service) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            <form action="{{ route('dashboard.services.destroy', $service) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                    <x-icon icon="trash-can" class="icon-20"/>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $services->links() }}
            </div>
        </div>
    </div>
@endsection 