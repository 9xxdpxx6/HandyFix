@extends('layouts.client')

@section('title', 'Мои заказы')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Мои заказы</h1>
            <a href="{{ route('order.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Новый заказ
            </a>
        </div>
        
        @if($orders->isEmpty())
            <div class="alert alert-info">
                <p class="mb-0">У вас пока нет заказов. Создайте свой первый заказ, нажав кнопку "Новый заказ".</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>№</th>
                            <th>Дата</th>
                            <th>Автомобиль</th>
                            <th>Услуги</th>
                            <th>Статус</th>
                            <th>Сумма</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                <td>{{ $order->vehicle->model->brand->name }} {{ $order->vehicle->model->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order->serviceEntries->count() }}</span>
                                </td>
                                <td>
                                    @if($order->status)
                                        <span class="badge bg-{{ $order->status->name === 'Новый' ? 'primary' : ($order->status->name === 'Оплачен' ? 'success' : 'secondary') }}">
                                            {{ $order->status->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Не указан</span>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($order->serviceEntries->sum(function($entry) { return $entry->price * $entry->quantity; }), 0, ',', ' ') }} ₽
                                </td>
                                <td>
                                    <a href="{{ route('order.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Детали
                                    </a>
                                    
                                    @if($order->status && $order->status->name === 'Новый')
                                        <a href="{{ route('order.payment', $order) }}" class="btn btn-sm btn-outline-success ms-1">
                                            <i class="fas fa-credit-card me-1"></i> Оплатить
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection 