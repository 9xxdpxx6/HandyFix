@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать автомобиль</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.vehicles.update', $vehicle) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="customer_id" class="form-label">Клиент</label>
                <select name="customer_id" id="customer_id" class="form-select">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $vehicle->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="model_id" class="form-label">Марка</label>
                <select name="model_id" id="model_id" class="form-select">
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $vehicle->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="model_id" class="form-label">Модель</label>
                <select name="model_id" id="model_id" class="form-select">
                    @foreach ($models as $model)
                        <option value="{{ $model->id }}" {{ $vehicle->model_id == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Год</label>
                <input type="number" name="year" id="year" class="form-control" value="{{ $vehicle->year }}" required>
            </div>

            <div class="mb-3">
                <label for="license_plate" class="form-label">Номерной знак</label>
                <input type="text" name="license_plate" id="license_plate" class="form-control" value="{{ $vehicle->license_plate }}" required>
            </div>

            <div class="mb-3">
                <label for="vin" class="form-label">VIN</label>
                <input type="text" name="vin" id="vin" class="form-control" value="{{ $vehicle->vin }}">
            </div>

            <div class="mb-3">
                <label for="mileage" class="form-label">Пробег</label>
                <input type="number" name="mileage" id="mileage" class="form-control" value="{{ $vehicle->mileage }}">
            </div>

            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
