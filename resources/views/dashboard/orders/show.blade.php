@extends('layouts.app')

@section('title', 'Просмотр заказа №' . $order->id)

@section('content')
    <div class="card">
        <div class="card-header d-md-flex align-items-center">
            <div>
                <h3 class="card-title mb-0 me-md-3">Детали заказа {{ $order->id }}</h3>
            </div>
            <div>
                <span class="badge" style="background-color: {{ $order->status->color ?? '#ccc' }};">
                    {{ $order->status->name ?? 'Н/Д' }}
                </span>
            </div>
            <div class="ms-auto">
                <span>Создан: {{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</span>
                @if($order->status->is_closing && $order->completed_at)
                    <span class="ms-md-3">Завершён: {{ \Carbon\Carbon::parse($order->completed_at)->format('d.m.Y H:i') }}</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Информация о клиенте -->
                <div class="col-md-6">
                    <h5>Информация о клиенте</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Имя клиента</th>
                            <td>{{ $order->customer->user->name ?? 'Н/Д' }}</td>
                        </tr>
                        <tr>
                            <th>Бонусные баллы</th>
                            <td>{{ $order->customer->loyalty_points ?? 'Н/Д' }}</td>
                        </tr>
                        <tr>
                            <th>Бонусная программа</th>
                            <td>{{ $order->customer->loyaltyLevel->name ?? 'Н/Д' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Информация об автомобиле -->
                <div class="col-md-6">
                    <h5>Информация об автомобиле</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Автомобиль</th>
                            <td>
                                <span>{{ $order->vehicle->model->brand->name ?? 'Н/Д' }}</span>
                                <span>{{ $order->vehicle->model->name ?? 'Н/Д' }}</span>
                                <span>{{ $order->vehicle->year ?? 'Н/Д' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>VIN-код</th>
                            <td>{{ $order->vehicle->vin ?? 'Н/Д' }}</td>
                        </tr>
                        <tr>
                            <th>Номерной знак</th>
                            <td>{{ $order->vehicle->license_plate ?? 'Н/Д' }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('dashboard.vehicles.show', $order->vehicle->id) }}" class="btn btn-primary">Просмотреть детали автомобиля</a>
                </div>
            </div>

            <!-- Покупки -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Покупки</h5>
                    @if($order->purchases->isEmpty())
                        <p>Для этого заказа покупки не найдены.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Название товара</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Итого</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->product_name }}</td>
                                    <td>${{ number_format($purchase->price, 2, ',', ' ') }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>${{ number_format($purchase->price * $purchase->quantity, 2, ',', ' ') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Услуги -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Услуги</h5>
                    @if($order->serviceEntries->isEmpty())
                        <p>Для этого заказа услуги не найдены.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Название услуги</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Итого</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->serviceEntries as $service)
                                <tr>
                                    <td>{{ $service->service_name }}</td>
                                    <td>${{ number_format($service->price, 2, ',', ' ') }}</td>
                                    <td>{{ $service->quantity }}</td>
                                    <td>${{ number_format($service->price * $service->quantity, 2, ',', ' ') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Комментарий и примечание -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5>Дополнительная информация</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Комментарий</th>
                            <td>{{ $order->comment ?? 'Комментарий отсутствует.' }}</td>
                        </tr>
                        <tr>
                            <th>Примечание</th>
                            <td>{{ $order->note ?? 'Примечание отсутствует.' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @can('update', $order)
                <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-warning">Редактировать</a>
            @endcan
            <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>

    <!-- Карточка с информацией о заказе -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0">Информация о заказе #{{ $order->id }}</h5>
            <div>
                @can('update', $order)
                    <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                @endcan
                
                @can('delete', $order)
                    <form action="{{ route('dashboard.orders.destroy', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
