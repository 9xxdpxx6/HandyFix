@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создать новый автомобиль</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.vehicles.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="customer_id" class="form-label">Клиент</label>
                <select name="customer_id" id="customer_id" class="form-select">
                    <option>Выберите клиента</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="model_id" class="form-label">Марка</label>
                <select name="model_id" id="model_id" class="form-select">
                    <option>Выберите марку</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="model_id" class="form-label">Модель</label>
                <select name="model_id" id="model_id" class="form-select">
                    <option>Выберите модель</option>
                    @foreach ($models as $model)
                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Год</label>
                <input type="number" name="year" id="year" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="license_plate" class="form-label">Номерной знак</label>
                <input type="text" name="license_plate" id="license_plate" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="vin" class="form-label">VIN</label>
                <input type="text" name="vin" id="vin" class="form-control">
            </div>

            <div class="mb-3">
                <label for="mileage" class="form-label">Пробег</label>
                <input type="number" name="mileage" id="mileage" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
