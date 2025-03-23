@extends('layouts.app')

@section('title', 'Информация о клиенте')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Информация о клиенте</h3>
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
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>Нет автомобилей, связанных с этим клиентом.</p>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('dashboard.customers.edit', $customer->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Редактировать
            </a>
            <a href="{{ route('dashboard.customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Вернуться к списку
            </a>
        </div>
    </div>
@endsection
