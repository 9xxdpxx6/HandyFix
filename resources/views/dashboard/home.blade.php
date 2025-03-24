@extends('layouts.app')

@section('content')
<!-- Статистика -->
<div class="row">
    <div class="col-sm-6 col-lg-4">
        <div class="card mb-4 text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $stats['orders_count'] ?? 0 }}</div>
                    <div>Заказов</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <a href="{{ route('dashboard.orders.index') }}" class="text-white"><x-icon icon="cart" class="icon-30"/></a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-4">
        <div class="card mb-4 text-white bg-info">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $stats['customers_count'] ?? 0 }}</div>
                    <div>Клиентов</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <a href="{{ route('dashboard.customers.index') }}" class="text-white"><x-icon icon="people" class="icon-30"/></a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-4">
        <div class="card mb-4 text-white bg-warning">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $stats['vehicles_count'] ?? 0 }}</div>
                    <div>Автомобилей</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <a href="{{ route('dashboard.vehicles.index') }}" class="text-white"><x-icon icon="car" class="icon-30"/></a>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>
<!-- /.row-->

<div class="row">
    <div class="col-sm-6 col-lg-4">
        <div class="card mb-4 text-white bg-danger">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $stats['employees_count'] ?? 0 }}</div>
                    <div>Сотрудников</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <a href="{{ route('dashboard.employees.index') }}" class="text-white"><x-icon icon="worker" class="icon-30"/></a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-4">
        <div class="card mb-4 text-white bg-success">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ $stats['products_count'] ?? 0 }}</div>
                    <div>Товаров</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <a href="{{ route('dashboard.products.index') }}" class="text-white"><x-icon icon="boxes" class="icon-30"/></a>
            </div>
        </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-lg-4">
        <div class="card mb-4 text-white bg-dark">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                <div>
                    <div class="fs-4 fw-semibold">{{ number_format($stats['orders_total'] ?? 0, 2, '.', ' ') }} ₽</div>
                    <div>Сумма заказов</div>
                </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
                <a href="{{ route('dashboard.statistics.finance') }}" class="text-white"><x-icon icon="chart" class="icon-30"/></a>
            </div>
        </div>
    </div>
    <!-- /.col-->
</div>
<!-- /.row-->

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="card-title mb-0">Статистика заказов</h4>
                <div class="small text-body-secondary">За последние 6 месяцев</div>
            </div>
        </div>
        <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
            <canvas class="chart" id="orders-chart" height="300"></canvas>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">Последние заказы</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border mb-0">
                        <thead class="table fw-semibold">
                            <tr class="align-middle">
                                <th class="text-center">№</th>
                                <th>Клиент</th>
                                <th class="text-center">Статус</th>
                                <th>Сумма</th>
                                <th class="text-center">Дата</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                            <tr class="align-middle">
                                <td class="text-center">{{ $order->id }}</td>
                                <td>{{ $order->customer->name ?? 'Не указан' }}</td>
                                <td class="text-center">
                                    <div class="badge bg-{{ $order->status->color ?? 'secondary' }}">{{ $order->status->name ?? 'Нет статуса' }}</div>
                                </td>
                                <td>{{ number_format($order->total, 2, '.', ' ') }} ₽</td>
                                <td class="text-center">{{ $order->created_at->format('d.m.Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('dashboard.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        <x-icon icon="eye" class="icon-20"/>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Нет данных</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($ordersChart ?? []);
    
    const labels = Object.keys(chartData);
    const data = Object.values(chartData);
    
    const ctx = document.getElementById('orders-chart');
    
    if(ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Количество заказов',
                    data: data,
                    backgroundColor: '#3b5998',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
@endpush
