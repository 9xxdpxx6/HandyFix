@extends('layouts.app')

@section('title', 'Список клиентов')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список клиентов</h3>
            <a href="{{ route('dashboard.customers.create') }}" class="btn btn-primary btn-sm">Добавить клиента</a>
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.customers.index') }}" class="row g-3 mb-3">
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
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Имя (А-Я)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Имя (Я-А)</option>
                        <option value="loyalty_points_asc" {{ request('sort') == 'loyalty_points_asc' ? 'selected' : '' }}>Баллы (по возрастанию)</option>
                        <option value="loyalty_points_desc" {{ request('sort') == 'loyalty_points_desc' ? 'selected' : '' }}>Баллы (по убыванию)</option>
                    </select>
                </div>

                <!-- Уровень лояльности -->
                <div class="col-md-2">
                    <label for="loyalty_level_id" class="form-label visually-hidden">Уровень лояльности</label>
                    <select name="loyalty_level_id" id="loyalty_level_id" class="form-select">
                        <option value="">Все уровни лояльности</option>
                        @foreach ($loyaltyLevels as $id => $name)
                            <option value="{{ $id }}" {{ request('loyalty_level_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Диапазон баллов лояльности -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" name="loyalty_points_min" id="loyalty_points_min" class="form-control" placeholder="Баллы от" value="{{ request('loyalty_points_min') }}" min="0">
                        <input type="number" name="loyalty_points_max" id="loyalty_points_max" class="form-control" placeholder="Баллы до" value="{{ request('loyalty_points_max') }}" min="0">
                    </div>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-4 ms-auto">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск по имени, email или телефону..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица клиентов -->
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Уровень лояльности</th>
                    <th>Баллы</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->user->name ?? 'Не указано' }}</td>
                        <td>{{ $customer->user->email ?? 'Не указано' }}</td>
                        <td>{{ $customer->user->phone ?? 'Не указано' }}</td>
                        <td>{{ $customer->loyaltyLevel->name ?? 'Не указано' }}</td>
                        <td>{{ $customer->loyalty_points }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20" />
                            </a>
                            <a href="{{ route('dashboard.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20" />
                            </a>
                            <form action="{{ route('dashboard.customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
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
                {{ $customers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
