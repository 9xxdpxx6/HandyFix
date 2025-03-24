@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список брендов</h3>
            <a href="{{ route('dashboard.brands.create') }}" class="btn btn-primary btn-sm">Добавить бренд</a>
        </div>

        <!-- Форма поиска -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.brands.index') }}" class="row g-3 mb-3">
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

                <!-- Страна регистрации -->
                <div class="col-md-2">
                    <label for="registration_country_code" class="form-label visually-hidden">Страна регистрации</label>
                    <select name="registration_country_code" id="registration_country_code" class="form-select">
                        <option value="">Страна регистрации</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}" {{ request('registration_country_code') == $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Страна производства -->
                <div class="col-md-2">
                    <label for="production_country_code" class="form-label visually-hidden">Страна производства</label>
                    <select name="production_country_code" id="production_country_code" class="form-select">
                        <option value="">Страна производства</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}" {{ request('production_country_code') == $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
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

        <!-- Таблица брендов -->
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th>Иконка</th>
                    <th>Описание</th>
                    <th class="text-center">Оригинал</th>
                    <th class="text-center">Страна регистрации</th>
                    <th class="text-center">Страна производства</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>
                            @if($brand->icon)
                                <div class="display-2 d-flex justify-content-center">
                                    <div class="bg-light rounded-2 p-1 icon-square">
                                        <i class="hf-icon {{ $brand->icon }}"></i>
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
                        <td>{{ $brand->description }}</td>
                        <td class="text-center">{{ $brand->is_original ? 'Да' : 'Нет' }}</td>
                        <td class="text-center">{{ $brand->registrationCountry?->name ?? 'Не указано' }}</td>
                        <td class="text-center">{{ $brand->productionCountry?->name ?? 'Не указано' }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.brands.show', $brand) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20" />
                            </a>
                            <a href="{{ route('dashboard.brands.edit', $brand) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20" />
                            </a>
                            <form action="{{ route('dashboard.brands.destroy', $brand) }}" method="POST" style="display:inline;">
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
                {{ $brands->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
