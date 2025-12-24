@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="h3 m-0 font-weight-bold">Статистика автомобилей</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Распределение по брендам</h6>
                            <x-export-buttons page="vehicles" report="vehicles_by_brand" />
                        </div>
                        <div class="card-body">
                            <div class="chart-pie">
                                <canvas id="vehiclesByBrandChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Распределение по годам выпуска</h6>
                            <x-export-buttons page="vehicles" report="vehicles_by_year" />
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="vehiclesByYearChart"></canvas>
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
                                        Всего автомобилей</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVehicles }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-car fa-2x text-gray-300"></i>
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
                                        Среднее кол-во заказов на автомобиль</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($ordersPerVehicle, 2, ',', ' ') }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Детальная статистика по брендам</h6>
                            <x-export-buttons page="vehicles" report="vehicles_by_brand" />
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Бренд</th>
                                            <th>Количество</th>
                                            <th>Процент</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vehiclesByBrand as $brand => $count)
                                        <tr>
                                            <td>{{ $brand }}</td>
                                            <td>{{ $count }}</td>
                                            <td>
                                                @if($totalVehicles > 0)
                                                    {{ number_format(($count / $totalVehicles) * 100, 1, ',', ' ') }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Топовые услуги по брендам</h6>
                            <x-export-buttons page="vehicles" report="top_services_by_brand" />
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($topServicesByBrand as $brand => $services)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6 class="m-0 font-weight-bold">{{ $brand }}</h6>
                                        </div>
                                        <div class="card-body">
                                            @if(count($services) > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($services as $service)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $service->service_name }}
                                                        <span class="badge bg-primary rounded-pill">{{ $service->total }}</span>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-center text-muted">Нет данных о услугах</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
    // Распределение по брендам (круговая диаграмма)
    const brandCtx = document.getElementById('vehiclesByBrandChart').getContext('2d');
    const brandLabels = [
        @foreach($vehiclesByBrand as $brand => $count)
            "{{ $brand }}",
        @endforeach
    ];
    const brandData = [
        @foreach($vehiclesByBrand as $count)
            {{ $count }},
        @endforeach
    ];
    
    // Генерация случайных цветов для брендов
    const brandColors = generateRandomColors(brandLabels.length);
    
    new Chart(brandCtx, {
        type: 'doughnut',
        data: {
            labels: brandLabels,
            datasets: [{
                data: brandData,
                backgroundColor: brandColors,
                hoverBackgroundColor: brandColors,
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

    // Распределение по годам выпуска (столбчатая диаграмма)
    const yearCtx = document.getElementById('vehiclesByYearChart').getContext('2d');
    const yearLabels = [
        @foreach($vehiclesByYear as $item)
            "{{ $item->year }}",
        @endforeach
    ];
    const yearData = [
        @foreach($vehiclesByYear as $item)
            {{ $item->total }},
        @endforeach
    ];
    
    new Chart(yearCtx, {
        type: 'bar',
        data: {
            labels: yearLabels,
            datasets: [{
                label: "Количество автомобилей",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: yearData,
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
                    beginAtZero: true
                }
            }
        },
    });

    // Функция для генерации случайных цветов
    function generateRandomColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * 137) % 360; // Золотое сечение для равномерного распределения цветов
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }
});
</script>
@endpush 