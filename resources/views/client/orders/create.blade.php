@extends('layouts.client')

@section('title', 'Новый заказ')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Мои заказы</a></li>
                <li class="breadcrumb-item active">Новый заказ</li>
            </ol>
        </nav>
        
        <h1 class="mb-4">Оформление нового заказа</h1>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('order.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">Выберите ваш автомобиль</label>
                                <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" required>
                                    <option value="">Выберите автомобиль</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->model->brand->name }} {{ $vehicle->model->name }} ({{ $vehicle->year }}, {{ $vehicle->license_plate }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Выберите услуги</label>
                                <div class="card">
                                    <div class="card-body px-0 py-0">
                                        <div class="list-group list-group-flush">
                                            @foreach($services->groupBy('serviceType.name') as $typeName => $typeServices)
                                                <div class="list-group-item">
                                                    <h6 class="mb-3">{{ $typeName }}</h6>
                                                    @foreach($typeServices as $service)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="service_ids[]" value="{{ $service->id }}" id="service_{{ $service->id }}" 
                                                                {{ (is_array(old('service_ids')) && in_array($service->id, old('service_ids'))) ? 'checked' : '' }}>
                                                            <label class="form-check-label d-flex justify-content-between" for="service_{{ $service->id }}">
                                                                <span>{{ $service->name }}</span>
                                                                <span class="ms-3 text-primary">{{ number_format($service->price, 0, ',', ' ') }} ₽</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @error('service_ids')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                @error('service_ids.*')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Дополнительные комментарии</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('order.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                                <button type="submit" class="btn btn-primary">Оформить заказ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Информация</h5>
                    </div>
                    <div class="card-body">
                        <p>После оформления заказа вы сможете:</p>
                        <ul>
                            <li>Отслеживать статус вашего заказа</li>
                            <li>Получать уведомления о ходе работ</li>
                            <li>Оплатить заказ онлайн через СБП</li>
                            <li>Оценить качество выполненных работ</li>
                        </ul>
                        <p class="text-muted small">После создания заказа с вами свяжется наш специалист для уточнения деталей и согласования времени.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 