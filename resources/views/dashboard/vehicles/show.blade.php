@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация об автомобиле #{{ $vehicle->id }}</h5>
                <div>
                    @can('update', $vehicle)
                    <a href="{{ route('dashboard.vehicles.edit', $vehicle) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $vehicle)
                    <form action="{{ route('dashboard.vehicles.destroy', $vehicle) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.vehicles.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Клиент:</strong> {{ $vehicle->customer->user->name ?? 'Не указан' }}</p>
                        <p><strong>Марка:</strong> {{ $vehicle->model->brand->name ?? 'Не указана' }}</p>
                        <p><strong>Модель:</strong> {{ $vehicle->model->name ?? 'Не указана' }}</p>
                        <p><strong>Год:</strong> {{ $vehicle->year }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Номерной знак:</strong> {{ $vehicle->license_plate }}</p>
                        <p><strong>VIN:</strong> {{ $vehicle->vin }}</p>
                        <p><strong>Пробег:</strong> {{ $vehicle->mileage }} км</p>
                    </div>
                </div>
            </div>
            
            @can('viewAny', \App\Models\Order::class)
            <div class="card-footer">
                <h6>История обслуживания:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Заказ</th>
                                <th>Услуги</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicle->orders as $order)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.orders.show', $order) }}">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>{{ $order->serviceEntries->count() }}</td>
                                <td>{{ number_format($order->total, 2, ',', ' ') }} ₽</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">История обслуживания отсутствует</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan
        </div>
    </div>
@endsection
