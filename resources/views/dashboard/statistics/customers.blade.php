@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="h3 m-0 font-weight-bold">Статистика клиентов</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.statistics.customers') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Начальная дата</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Конечная дата</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Применить</button>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Новые клиенты</h6>
                            <x-export-buttons page="customers" report="new_customers_by_date" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="newCustomersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Клиенты по уровням лояльности</h6>
                            <x-export-buttons page="customers" report="customers_by_loyalty" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="chart-pie">
                                <canvas id="loyaltyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border-left-primary shadow h-100 py-2 mb-4">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Всего клиентов</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-left-success shadow h-100 py-2 mb-4">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Новых клиентов за период</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newCustomers }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Топ 10 клиентов по количеству заказов</h6>
                            <x-export-buttons page="customers" report="top_customers_by_orders" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Клиент</th>
                                            <th>Заказов</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topCustomersByOrders as $customer)
                                        <tr>
                                            <td>{{ $customer->user->name ?? 'Не указано' }}</td>
                                            <td>{{ $customer->orders_count }}</td>
                                        </tr>
                                        @endforeach
                                        @if(count($topCustomersByOrders) == 0)
                                        <tr>
                                            <td colspan="2" class="text-center">Нет данных</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Топ 10 клиентов по сумме заказов</h6>
                            <x-export-buttons page="customers" report="top_customers_by_total" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Клиент</th>
                                            <th>Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topCustomersByTotal as $customer)
                                        <tr>
                                            <td>{{ $customer->user->name ?? 'Не указано' }}</td>
                                            <td>{{ number_format($customer->total_spent, 2, ',', ' ') }} ₽</td>
                                        </tr>
                                        @endforeach
                                        @if(count($topCustomersByTotal) == 0)
                                        <tr>
                                            <td colspan="2" class="text-center">Нет данных</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
    // Новые клиенты (линейная диаграмма)
    const newCustomersCtx = document.getElementById('newCustomersChart').getContext('2d');
    const newCustomersLabels = [
        @foreach($newCustomersByDate as $item)
            "{{ \Carbon\Carbon::parse($item->date)->format('d.m.Y') }}",
        @endforeach
    ];
    const newCustomersData = [
        @foreach($newCustomersByDate as $item)
            {{ $item->total }},
        @endforeach
    ];
    
    new Chart(newCustomersCtx, {
        type: 'line',
        data: {
            labels: newCustomersLabels,
            datasets: [{
                label: "Новые клиенты",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: newCustomersData,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        },
    });

    // Клиенты по уровням лояльности (круговая диаграмма)
    const loyaltyCtx = document.getElementById('loyaltyChart').getContext('2d');
    const loyaltyLabels = [
        @foreach($customersByLoyalty as $item)
            "{{ $item->level_name }}",
        @endforeach
    ];
    const loyaltyData = [
        @foreach($customersByLoyalty as $item)
            {{ $item->total }},
        @endforeach
    ];
    
    // Генерация цветов для уровней лояльности
    const loyaltyColors = generateColorGradient(loyaltyLabels.length, [255, 99, 132], [54, 162, 235]);
    
    new Chart(loyaltyCtx, {
        type: 'doughnut',
        data: {
            labels: loyaltyLabels,
            datasets: [{
                data: loyaltyData,
                backgroundColor: loyaltyColors,
                hoverBackgroundColor: loyaltyColors,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
        },
    });
    
    // Функция для генерации градиента цветов
    function generateColorGradient(count, startColor, endColor) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const ratio = i / (count - 1);
            const r = Math.round(startColor[0] + ratio * (endColor[0] - startColor[0]));
            const g = Math.round(startColor[1] + ratio * (endColor[1] - startColor[1]));
            const b = Math.round(startColor[2] + ratio * (endColor[2] - startColor[2]));
            colors.push(`rgb(${r}, ${g}, ${b})`);
        }
        return colors;
    }
});
</script>
@endpush