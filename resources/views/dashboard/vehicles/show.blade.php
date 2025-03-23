@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Информация об автомобиле</h1>

        <p><strong>ID:</strong> {{ $vehicle->id }}</p>
        <p><strong>Клиент:</strong> {{ $vehicle->customer->user->name ?? 'N/A' }}</p>
        <p><strong>Марка:</strong> {{ $vehicle->model->brand->name ?? 'N/A' }}</p>
        <p><strong>Модель:</strong> {{ $vehicle->model->name ?? 'N/A' }}</p>
        <p><strong>Год:</strong> {{ $vehicle->year }}</p>
        <p><strong>Номерной знак:</strong> {{ $vehicle->license_plate }}</p>
        <p><strong>VIN:</strong> {{ $vehicle->vin }}</p>
        <p><strong>Пробег:</strong> {{ $vehicle->mileage }}</p>

        <a href="{{ route('dashboard.vehicles.edit', $vehicle) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('dashboard.vehicles.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
