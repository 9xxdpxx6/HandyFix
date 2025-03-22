@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="h3 m-0 font-weight-bold">Финансовая статистика</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.statistics.finance') }}" class="mb-4">
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
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Общий доход</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRevenue, 2, ',', ' ') }} ₽</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Доход от услуг</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($serviceRevenue, 2, ',', ' ') }} ₽</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tools fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Доход от товаров</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($productRevenue, 2, ',', ' ') }} ₽</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Средний чек</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($averageOrderTotal, 2, ',', ' ') }} ₽</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Динамика дохода</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Доход по категориям товаров</h6>
                            <div class="chart-mode-switch">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary active" data-chart="productCategoriesChart" data-mode="revenue">По цене</button>
                                    <button type="button" class="btn btn-outline-primary" data-chart="productCategoriesChart" data-mode="quantity">По количеству</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-radar">
                                <canvas id="productCategoriesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Доход по типам услуг</h6>
                            <div class="chart-mode-switch">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary active" data-chart="serviceTypesChart" data-mode="revenue">По цене</button>
                                    <button type="button" class="btn btn-outline-primary" data-chart="serviceTypesChart" data-mode="quantity">По количеству</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-radar">
                                <canvas id="serviceTypesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Ключевые показатели</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Количество заказов</div>
                                                    <div class="h5 mb-0 font-weight-bold">{{ $ordersCount }}</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-clipboard-list fa-2x text-gray-500"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Доход на заказ</div>
                                                    <div class="h5 mb-0 font-weight-bold">{{ number_format($totalRevenue / ($ordersCount ?: 1), 2, ',', ' ') }} ₽</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-chart-line fa-2x text-gray-500"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Доход в день</div>
                                                    <div class="h5 mb-0 font-weight-bold">
                                                        @if(count($revenueByDate) > 0)
                                                            {{ number_format($totalRevenue / count($revenueByDate), 2, ',', ' ') }} ₽
                                                        @else
                                                            0 ₽
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-calendar-day fa-2x text-gray-500"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                        Заказов в день</div>
                                                    <div class="h5 mb-0 font-weight-bold">
                                                        @if(count($revenueByDate) > 0)
                                                            {{ number_format($ordersCount / count($revenueByDate), 2, ',', ' ') }}
                                                        @else
                                                            0
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-business-time fa-2x text-gray-500"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    // Динамика дохода (линейная диаграмма)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueLabels = [
        @foreach($revenueByDate as $item)
            "{{ \Carbon\Carbon::parse($item->date)->format('d.m.Y') }}",
        @endforeach
    ];
    const revenueData = [
        @foreach($revenueByDate as $item)
            {{ $item->total_revenue }},
        @endforeach
    ];
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: "Доход (₽)",
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
                data: revenueData,
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
                        callback: function(value) {
                            return value.toLocaleString('ru-RU') + ' ₽';
                        }
                    }
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return tooltipItem.yLabel.toLocaleString('ru-RU') + ' ₽';
                    }
                }
            }
        },
    });

    // Данные для диаграмм категорий товаров
    const productCtx = document.getElementById('productCategoriesChart').getContext('2d');
    const productLabels = [
        @foreach($revenueByProductCategory as $item)
            "{{ $item->category_name }}",
        @endforeach
    ];
    const productRevenueData = [
        @foreach($revenueByProductCategory as $item)
            {{ $item->revenue }},
        @endforeach
    ];
    const productQuantityData = [
        @foreach($revenueByProductCategory as $item)
            {{ $item->quantity }},
        @endforeach
    ];
    const productQuantities = [
        @foreach($revenueByProductCategory as $item)
            {{ $item->quantity }},
        @endforeach
    ];
    
    // Диаграмма категорий товаров
    let productChart = new Chart(productCtx, {
        type: 'polarArea',
        data: {
            labels: productLabels,
            datasets: [{
                label: "Доход от категорий товаров (₽)",
                backgroundColor: [
                    "rgba(54, 162, 235, 0.6)",
                    "rgba(255, 99, 132, 0.6)",
                    "rgba(75, 192, 192, 0.6)",
                    "rgba(255, 206, 86, 0.6)",
                    "rgba(153, 102, 255, 0.6)",
                    "rgba(255, 159, 64, 0.6)"
                ],
                borderColor: [
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 99, 132, 1)",
                    "rgba(75, 192, 192, 1)",
                    "rgba(255, 206, 86, 1)",
                    "rgba(153, 102, 255, 1)",
                    "rgba(255, 159, 64, 1)"
                ],
                borderWidth: 1,
                data: productRevenueData,
            }],
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: {
                        display: false,
                        backdropColor: 'rgba(0, 0, 0, 0)'
                    },
                    pointLabels: {
                        font: {
                            size: 14
                        },
                        callback: function(value) {
                            // Добавляем перенос строк каждые 10-15 символов
                            return value.length > 12 ? 
                                value.match(/.{1,12}(\s|$)/g).join('\n') : 
                                value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            const mode = document.querySelector('.btn-group button.active[data-chart="productCategoriesChart"]').dataset.mode;
                            if (context.raw !== null) {
                                if (mode === 'revenue') {
                                    label += context.raw.toLocaleString('ru-RU') + ' ₽';
                                } else {
                                    label += context.raw.toLocaleString('ru-RU') + ' шт.';
                                }
                                
                                // Добавляем дополнительную информацию в зависимости от режима
                                if (mode === 'revenue') {
                                    label += ' (' + productQuantities[context.dataIndex].toLocaleString('ru-RU') + ' шт.)';
                                } else {
                                    label += ' (' + productRevenueData[context.dataIndex].toLocaleString('ru-RU') + ' ₽)';
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        },
    });
    
    // Данные для диаграмм типов услуг
    const serviceCtx = document.getElementById('serviceTypesChart').getContext('2d');
    const serviceLabels = [
        @foreach($revenueByServiceType as $item)
            "{{ $item->type_name }}",
        @endforeach
    ];
    const serviceRevenueData = [
        @foreach($revenueByServiceType as $item)
            {{ $item->revenue }},
        @endforeach
    ];
    const serviceQuantityData = [
        @foreach($revenueByServiceType as $item)
            {{ $item->quantity }},
        @endforeach
    ];
    const serviceQuantities = [
        @foreach($revenueByServiceType as $item)
            {{ $item->quantity }},
        @endforeach
    ];
    
    // Диаграмма типов услуг
    let serviceChart = new Chart(serviceCtx, {
        type: 'polarArea',
        data: {
            labels: serviceLabels,
            datasets: [{
                label: "Доход от типов услуг (₽)",
                backgroundColor: [
                    "rgba(255, 99, 132, 0.6)",
                    "rgba(54, 162, 235, 0.6)",
                    "rgba(75, 192, 192, 0.6)",
                    "rgba(255, 206, 86, 0.6)",
                    "rgba(153, 102, 255, 0.6)",
                    "rgba(255, 159, 64, 0.6)"
                ],
                borderColor: [
                    "rgba(255, 99, 132, 1)",
                    "rgba(54, 162, 235, 1)",
                    "rgba(75, 192, 192, 1)",
                    "rgba(255, 206, 86, 1)",
                    "rgba(153, 102, 255, 1)",
                    "rgba(255, 159, 64, 1)"
                ],
                borderWidth: 1,
                data: serviceRevenueData,
            }],
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: {
                        display: false,
                        backdropColor: 'rgba(0, 0, 0, 0)'
                    },
                    pointLabels: {
                        font: {
                            size: 14
                        },
                        callback: function(value) {
                            // Добавляем перенос строк каждые 10-15 символов
                            return value.length > 12 ? 
                                value.match(/.{1,12}(\s|$)/g).join('\n') : 
                                value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            const mode = document.querySelector('.btn-group button.active[data-chart="serviceTypesChart"]').dataset.mode;
                            if (context.raw !== null) {
                                if (mode === 'revenue') {
                                    label += context.raw.toLocaleString('ru-RU') + ' ₽';
                                } else {
                                    label += context.raw.toLocaleString('ru-RU') + ' шт.';
                                }
                                
                                // Добавляем дополнительную информацию в зависимости от режима
                                if (mode === 'revenue') {
                                    label += ' (' + serviceQuantities[context.dataIndex].toLocaleString('ru-RU') + ' шт.)';
                                } else {
                                    label += ' (' + serviceRevenueData[context.dataIndex].toLocaleString('ru-RU') + ' ₽)';
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        },
    });
    
    // Обработчик переключения режимов отображения
    document.querySelectorAll('.chart-mode-switch .btn-group button').forEach(button => {
        button.addEventListener('click', function() {
            const chartId = this.dataset.chart;
            const mode = this.dataset.mode;
            
            // Обновляем активную кнопку
            document.querySelectorAll(`.btn-group button[data-chart="${chartId}"]`).forEach(btn => {
                btn.classList.remove('btn-primary', 'active', 'btn-outline-primary');
                btn.classList.add(btn === this ? 'btn-primary' : 'btn-outline-primary');
                if (btn === this) {
                    btn.classList.add('active');
                }
            });
            
            // Обновляем данные диаграммы
            if (chartId === 'productCategoriesChart') {
                productChart.data.datasets[0].data = mode === 'revenue' ? productRevenueData : productQuantityData;
                productChart.data.datasets[0].label = mode === 'revenue' ? 
                    "Доход от категорий товаров (₽)" : 
                    "Количество товаров по категориям (шт.)";
                productChart.update();
            } else if (chartId === 'serviceTypesChart') {
                serviceChart.data.datasets[0].data = mode === 'revenue' ? serviceRevenueData : serviceQuantityData;
                serviceChart.data.datasets[0].label = mode === 'revenue' ? 
                    "Доход от типов услуг (₽)" : 
                    "Количество услуг по типам (шт.)";
                serviceChart.update();
            }
        });
    });
});
</script>
@endpush 