@extends('layouts.app')

@section('title', 'Информация о клиенте')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Информация о клиенте</h3>
            <div>
                @can('update', $customer)
                <a href="{{ route('dashboard.customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                    <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                </a>
                @endcan
                
                @can('delete', $customer)
                <form action="{{ route('dashboard.customers.destroy', $customer) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                        <x-icon icon="trash-can" class="icon-20"/> Удалить
                    </button>
                </form>
                @endcan
                
                <a href="{{ route('dashboard.customers.index') }}" class="btn btn-secondary btn-sm">
                    <x-icon icon="arrow-left" class="icon-20"/> Назад
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $customer->id }}</p>
                    <p><strong>Имя:</strong> {{ $customer->user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $customer->user->email ?? 'N/A' }}</p>
                    <p><strong>Телефон:</strong> {{ $customer->user->phone ?? 'N/A' }}</p>
                    <p><strong>Бонусные баллы:</strong> {{ $customer->loyalty_points }}</p>
                    <p><strong>Бонусная программа:</strong> {{ $customer->loyaltyLevel->name ?? 'N/A' }}</p>
                    <p><strong>Информация:</strong> {{ $customer->info ?? 'Нет дополнительной информации' }}</p>
                </div>
            </div>

            <!-- Раздел автомобилей -->
            <hr>
            <h4>Автомобили</h4>
            @if($customer->vehicles->isNotEmpty())
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Марка</th>
                        <th>Модель</th>
                        <th>Год</th>
                        <th>Номерной знак</th>
                        <th>VIN</th>
                        <th>Пробег</th>
                        @can('viewAny', \App\Models\Vehicle::class)
                        <th>Действия</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customer->vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->model->brand->name ?? 'N/A' }}</td>
                            <td>{{ $vehicle->model->name }}</td>
                            <td>{{ $vehicle->year }}</td>
                            <td>{{ $vehicle->license_plate }}</td>
                            <td>{{ $vehicle->vin ?? 'N/A' }}</td>
                            <td>{{ $vehicle->mileage ?? 'N/A' }}</td>
                            @can('viewAny', \App\Models\Vehicle::class)
                            <td>
                                <a href="{{ route('dashboard.vehicles.show', $vehicle) }}" class="btn btn-sm btn-outline-info">
                                    <x-icon icon="eye" class="icon-20"/>
                                </a>
                                @can('update', $vehicle)
                                <a href="{{ route('dashboard.vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-warning">
                                    <x-icon icon="pencil-square" class="icon-20"/>
                                </a>
                                @endcan
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>Нет автомобилей, связанных с этим клиентом.</p>
            @endif
        </div>
    </div>
@endsection
