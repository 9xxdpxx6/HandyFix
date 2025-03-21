@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список автомобилей</h3>
            <a href="{{ route('dashboard.vehicles.create') }}" class="btn btn-primary btn-sm">Добавить автомобиль</a>
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.vehicles.index') }}" class="row g-3 mb-3">
                <!-- Количество элементов на странице -->
                <div class="col-md-2">
                    <label for="limit" class="form-label visually-hidden">Отображать по</label>
                    <select name="limit" id="limit" class="form-select">
                        <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <!-- Сортировка -->
                <div class="col-md-2">
                    <label for="sort" class="form-label visually-hidden">Сортировка</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>По умолчанию</option>
                        <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Год (по возрастанию)</option>
                        <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Год (по убыванию)</option>
                        <option value="license_plate_asc" {{ request('sort') == 'license_plate_asc' ? 'selected' : '' }}>Госномер (А-Я)</option>
                        <option value="license_plate_desc" {{ request('sort') == 'license_plate_desc' ? 'selected' : '' }}>Госномер (Я-А)</option>
                    </select>
                </div>

                <!-- Бренд -->
                <div class="col-md-2">
                    <label for="brand_id" class="form-label visually-hidden">Марка</label>
                    <select name="brand_id" id="brand_id" class="form-select">
                        <option value="">Все марки</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Модель -->
                <div class="col-md-2">
                    <label for="model_id" class="form-label visually-hidden">Модель</label>
                    <select name="model_id" id="model_id" class="form-select">
                        <option value="">Все модели</option>
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>
                                {{ $model->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Клиент -->
                <div class="col-md-2">
                    <label for="customer_id" class="form-label visually-hidden">Клиент</label>
                    <select name="customer_id" id="customer_id" class="form-select">
                        <option value="">Все клиенты</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->user->name ?? 'Неизвестный клиент' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Диапазон годов выпуска -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" name="year_min" id="year_min" class="form-control" placeholder="Год от" value="{{ request('year_min') }}" min="1900" max="{{ date('Y') }}">
                        <input type="number" name="year_max" id="year_max" class="form-control" placeholder="Год до" value="{{ request('year_max') }}" min="1900" max="{{ date('Y') }}">
                    </div>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-4 ms-auto">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск по госномеру или VIN..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица автомобилей -->
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Госномер</th>
                    <th>Клиент</th>
                    <th>Марка</th>
                    <th>Модель</th>
                    <th>Год</th>
                    <th>VIN</th>
                    <th>Пробег</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->id }}</td>
                        <td>{{ $vehicle->license_plate }}</td>
                        <td>{{ $vehicle->customer->user->name ?? 'Не указан' }}</td>
                        <td>{{ $vehicle->model->brand->name ?? 'Не указан' }}</td>
                        <td>{{ $vehicle->model->name ?? 'Не указана' }}</td>
                        <td>{{ $vehicle->year }}</td>
                        <td>{{ $vehicle->vin }}</td>
                        <td>{{ $vehicle->mileage }} км</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            <a href="{{ route('dashboard.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            <form action="{{ route('dashboard.vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline;">
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
                {{ $vehicles->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
