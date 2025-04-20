@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список моделей автомобилей</h3>
            @can('create', \App\Models\VehicleModel::class)
            <a href="{{ route('dashboard.models.create') }}" class="btn btn-primary btn-sm">Добавить модель</a>
            @endcan
        </div>

        <!-- Форма поиска -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.models.index') }}" class="row g-3 mb-3">
                <!-- Количество элементов на странице -->
                <div class="col-md-2">
                    <label for="limit" class="form-label visually-hidden">Отображать по</label>
                    <select name="limit" id="limit" class="form-select">
                        <option value="25" {{ isset($data['limit']) && $data['limit'] == 25 ? 'selected' : '' }}>Отображать по 25</option>
                        <option value="50" {{ isset($data['limit']) && $data['limit'] == 50 ? 'selected' : '' }}>Отображать по 50</option>
                        <option value="100" {{ isset($data['limit']) && $data['limit'] == 100 ? 'selected' : '' }}>Отображать по 100</option>
                    </select>
                </div>

                <!-- Сортировка -->
                <div class="col-md-2">
                    <label for="sort" class="form-label visually-hidden">Сортировка</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="default" {{ isset($data['sort']) && $data['sort'] == 'default' ? 'selected' : '' }}>По умолчанию</option>
                        <option value="alphabet_asc" {{ isset($data['sort']) && $data['sort'] == 'alphabet_asc' ? 'selected' : '' }}>А-Я</option>
                        <option value="alphabet_desc" {{ isset($data['sort']) && $data['sort'] == 'alphabet_desc' ? 'selected' : '' }}>Я-А</option>
                        <option value="year_asc" {{ isset($data['sort']) && $data['sort'] == 'year_asc' ? 'selected' : '' }}>По году (старые сначала)</option>
                        <option value="year_desc" {{ isset($data['sort']) && $data['sort'] == 'year_desc' ? 'selected' : '' }}>По году (новые сначала)</option>
                    </select>
                </div>

                <!-- Бренд -->
                <div class="col-md-2">
                    <label for="brand_id" class="form-label visually-hidden">Бренд</label>
                    <select name="brand_id" id="brand_id" class="form-select">
                        <option value="">Все бренды</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ isset($data['brand_id']) && $data['brand_id'] == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Год начала производства (от) -->
                <div class="col-md-2">
                    <label for="start_year_min" class="form-label visually-hidden">Год начала от</label>
                    <input type="number" class="form-control" id="start_year_min" name="start_year_min" 
                        placeholder="Год начала от" min="1900" max="{{ date('Y') + 1 }}" 
                        value="{{ $data['start_year_min'] ?? '' }}">
                </div>

                <!-- Год начала производства (до) -->
                <div class="col-md-2">
                    <label for="start_year_max" class="form-label visually-hidden">Год начала до</label>
                    <input type="number" class="form-control" id="start_year_max" name="start_year_max" 
                        placeholder="Год начала до" min="1900" max="{{ date('Y') + 1 }}" 
                        value="{{ $data['start_year_max'] ?? '' }}">
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" 
                            placeholder="Поиск..." value="{{ $data['keyword'] ?? '' }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица моделей -->
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th>Бренд</th>
                    <th>Поколение</th>
                    <th class="text-center">Годы выпуска</th>
                    <th class="text-center">Рестайлинг</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($models as $vehicleModel)
                    <tr>
                        <td>{{ $vehicleModel->id }}</td>
                        <td>{{ $vehicleModel->name }}</td>
                        <td>{{ $vehicleModel->brand->name ?? 'Не указан' }}</td>
                        <td>{{ $vehicleModel->generation ?? 'Не указано' }}</td>
                        <td class="text-center">
                            {{ $vehicleModel->start_year }} - {{ $vehicleModel->end_year ?? 'наст. время' }}
                        </td>
                        <td class="text-center">
                            @if($vehicleModel->is_facelift)
                                Да ({{ $vehicleModel->facelift_year ?? 'год не указан' }})
                            @else
                                Нет
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('dashboard.models.show', $vehicleModel) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20" />
                            </a>
                            
                            @can('update', $vehicleModel)
                            <a href="{{ route('dashboard.models.edit', $vehicleModel) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20" />
                            </a>
                            @endcan
                            
                            @can('delete', $vehicleModel)
                            <form action="{{ route('dashboard.models.destroy', $vehicleModel) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены? Это может повлиять на связанные автомобили.')">
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
                {{ $models->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection 