@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список заказов</h3>
            @can('create', \App\Models\Order::class)
            <a href="{{ route('dashboard.orders.create') }}" class="btn btn-primary btn-sm">Добавить заказ</a>
            @endcan
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.orders.index') }}" class="row g-3 mb-3">
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
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Дата (по возрастанию)</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Дата (по убыванию)</option>
                        <option value="total_asc" {{ request('sort') == 'total_asc' ? 'selected' : '' }}>Сумма (по возрастанию)</option>
                        <option value="total_desc" {{ request('sort') == 'total_desc' ? 'selected' : '' }}>Сумма (по убыванию)</option>
                    </select>
                </div>

                <!-- Статус -->
                <div class="col-md-2">
                    <label for="status_id" class="form-label visually-hidden">Статус</label>
                    <select name="status_id" id="status_id" class="form-select">
                        <option value="">Все статусы</option>
                        @foreach ($statuses as $id => $name)
                            <option value="{{ $id }}" {{ request('status_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Клиент -->
                <div class="col-md-2">
                    <label for="customer_id" class="form-label visually-hidden">Клиент</label>
                    <select name="customer_id" id="customer_id" class="form-select">
                        <option value="">Все клиенты</option>
                        @foreach ($customers as $id => $customerId)
                            <option value="{{ $id }}" {{ request('customer_id') == $id ? 'selected' : '' }}>
                                Клиент №{{ $customerId }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Автомобиль -->
                <div class="col-md-2">
                    <label for="vehicle_id" class="form-label visually-hidden">Автомобиль</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-select">
                        <option value="">Все автомобили</option>
                        @foreach ($vehicles as $id => $plate)
                            <option value="{{ $id }}" {{ request('vehicle_id') == $id ? 'selected' : '' }}>
                                {{ $plate }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Диапазон сумм -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" name="total_min" id="total_min" class="form-control" placeholder="Сумма от" value="{{ request('total_min') }}" min="0">
                        <input type="number" name="total_max" id="total_max" class="form-control" placeholder="Сумма до" value="{{ request('total_max') }}" min="0">
                    </div>
                </div>

                <!-- Диапазон дат -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="date" name="date_from" id="date_from" class="form-control" placeholder="Дата с" value="{{ request('date_from') }}">
                        <input type="date" name="date_to" id="date_to" class="form-control" placeholder="Дата по" value="{{ request('date_to') }}">
                    </div>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-4 ms-auto">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск по комментарию или примечанию..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица заказов -->
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Дата</th>
                    <th>Клиент</th>
                    <th>Автомобиль</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</td>
                        <td>{{ $order->customer->user->name ?? 'Не указан' }}</td>
                        <td>
                            @if ($order->vehicle)
                                {{ $order->vehicle->model->brand->name ?? '' }} 
                                {{ $order->vehicle->model->name ?? '' }}
                                ({{ $order->vehicle->license_plate ?? '' }})
                            @else
                                Не указан
                            @endif
                        </td>
                        <td>{{ number_format($order->total, 2, ',', ' ') }} ₽</td>
                        <td>{{ $order->status->name ?? 'Не указан' }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20" />
                            </a>
                            
                            @can('update', $order)
                            <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20" />
                            </a>
                            @endcan
                            
                            @can('delete', $order)
                            <form action="{{ route('dashboard.orders.destroy', $order) }}" method="POST" style="display:inline;">
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
            <div class="d-flex justify-content-between">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ColorModes.init({ el: document.documentElement });

            document.documentElement.setAttribute('data-color-mode', 'dark');
        });
    </script>
@endpush
