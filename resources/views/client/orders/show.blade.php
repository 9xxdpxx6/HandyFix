@extends('layouts.client')

@section('title', 'Заказ №' . $order->id)

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Мои заказы</a></li>
                <li class="breadcrumb-item active">Заказ №{{ $order->id }}</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Заказ №{{ $order->id }}</h1>
            @if($order->status && $order->status->name === 'Новый')
                <a href="{{ route('order.payment', $order) }}" class="btn btn-success">
                    <i class="fas fa-credit-card me-1"></i> Оплатить заказ
                </a>
            @endif
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Информация о заказе -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Информация о заказе</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted">Дата создания:</p>
                                <p class="fw-bold">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted">Статус:</p>
                                <p>
                                    @if($order->status)
                                        <span class="badge bg-{{ $order->status->name === 'Новый' ? 'primary' : ($order->status->name === 'Оплачен' ? 'success' : 'secondary') }} p-2">
                                            {{ $order->status->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary p-2">Не указан</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted">Автомобиль:</p>
                                <p class="fw-bold">{{ $order->vehicle->model->brand->name }} {{ $order->vehicle->model->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted">Гос. номер:</p>
                                <p class="fw-bold">{{ $order->vehicle->license_plate }}</p>
                            </div>
                        </div>
                        
                        @if($order->description)
                            <div class="mb-0">
                                <p class="mb-1 text-muted">Дополнительная информация:</p>
                                <p class="mb-0">{{ $order->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Услуги -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Услуги</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Наименование</th>
                                        <th>Цена</th>
                                        <th class="text-center">Кол-во</th>
                                        <th class="text-end">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->serviceEntries as $index => $entry)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $entry->service->name }}</td>
                                            <td>{{ number_format($entry->price, 0, ',', ' ') }} ₽</td>
                                            <td class="text-center">{{ $entry->quantity }}</td>
                                            <td class="text-end">{{ number_format($entry->price * $entry->quantity, 0, ',', ' ') }} ₽</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Итого:</th>
                                        <th class="text-end">
                                            {{ number_format($order->serviceEntries->sum(function($entry) { return $entry->price * $entry->quantity; }), 0, ',', ' ') }} ₽
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Статус заказа -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Статус заказа</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Ожидание оплаты</span>
                                @if($order->status && in_array($order->status->name, ['Новый', 'Оплачен', 'В работе', 'Завершен']))
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-circle text-secondary"></i>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Оплачен</span>
                                @if($order->status && in_array($order->status->name, ['Оплачен', 'В работе', 'Завершен']))
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-circle text-secondary"></i>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>В работе</span>
                                @if($order->status && in_array($order->status->name, ['В работе', 'Завершен']))
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-circle text-secondary"></i>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Завершен</span>
                                @if($order->status && $order->status->name === 'Завершен')
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-circle text-secondary"></i>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Действия -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Действия</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('order.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> К списку заказов
                            </a>
                            
                            @if($order->status && $order->status->name === 'Новый')
                                <a href="{{ route('order.payment', $order) }}" class="btn btn-success">
                                    <i class="fas fa-credit-card me-1"></i> Оплатить заказ
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 