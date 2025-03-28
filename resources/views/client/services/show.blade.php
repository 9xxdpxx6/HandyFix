@extends('layouts.client')

@section('title', $service->name)

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Услуги</a></li>
                <li class="breadcrumb-item active">{{ $service->name }}</li>
            </ol>
        </nav>
        
        <div class="row">
            <div class="col-lg-8">
                <h1 class="mb-3">{{ $service->name }}</h1>
                <div class="mb-4">
                    <span class="badge bg-primary">{{ $service->serviceType->name }}</span>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Описание услуги</h5>
                        <p class="card-text">{{ $service->description }}</p>
                    </div>
                </div>
                
                <div class="text-center mb-4">
                    <h4 class="mb-3">Заинтересовала эта услуга?</h4>
                    @auth
                        <a href="{{ route('order.create') }}" class="btn btn-primary btn-lg">Оформить заказ</a>
                    @else
                        <div class="alert alert-info">
                            Для оформления заказа необходимо <a href="{{ route('login') }}" class="alert-link">войти</a> или 
                            <a href="{{ route('register') }}" class="alert-link">зарегистрироваться</a>.
                        </div>
                    @endauth
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Стоимость</h5>
                        <p class="display-6 fw-bold text-primary mb-4">{{ number_format($service->price, 0, ',', ' ') }} ₽</p>
                        <p class="card-text text-muted">Итоговая стоимость работ может зависеть от особенностей вашего автомобиля и сложности случая.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Преимущества</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Профессиональное оборудование
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Опытные специалисты
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Гарантия на все работы
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Удобная запись онлайн
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 