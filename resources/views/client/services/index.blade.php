@extends('layouts.client')

@section('title', 'Услуги')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Наши услуги</h1>
        
        <!-- Фильтры и поиск -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('services.index') }}" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label for="search" class="form-label">Поиск</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Название или описание услуги">
                    </div>
                    <div class="col-md-5">
                        <label for="type_id" class="form-label">Тип услуги</label>
                        <select class="form-select" id="type_id" name="type_id">
                            <option value="">Все типы</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Найти</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Список услуг -->
        <div class="row g-4">
            @forelse($services as $service)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 service-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $service->serviceType->name }}</h6>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($service->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary fs-5">{{ number_format($service->price, 0, ',', ' ') }} ₽</span>
                                <a href="{{ route('services.show', $service) }}" class="btn btn-outline-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        По вашему запросу не найдено ни одной услуги. 
                        <a href="{{ route('services.index') }}" class="alert-link">Сбросить фильтры</a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Пагинация -->
        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
@endsection 