@extends('layouts.client')

@section('title', 'Мои автомобили')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Мои автомобили</h1>
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Добавить автомобиль
            </a>
        </div>
        
        @if($vehicles->isEmpty())
            <div class="alert alert-info">
                <p class="mb-0">У вас пока нет добавленных автомобилей. Нажмите кнопку "Добавить автомобиль", чтобы добавить свой первый автомобиль.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($vehicles as $vehicle)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $vehicle->model->brand->name }} {{ $vehicle->model->name }}</h5>
                                <p class="card-text">
                                    <strong>Год выпуска:</strong> {{ $vehicle->year }}<br>
                                    <strong>Гос. номер:</strong> {{ $vehicle->license_plate }}<br>
                                    <strong>VIN:</strong> {{ $vehicle->vin ?: 'Не указан' }}<br>
                                    <strong>Пробег:</strong> {{ number_format($vehicle->mileage, 0, ',', ' ') }} км
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Редактировать
                                </a>
                                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Вы уверены, что хотите удалить этот автомобиль?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt me-1"></i> Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection 