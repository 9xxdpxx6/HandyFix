@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="h3 m-0 font-weight-bold">Статистика товаров</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.statistics.products') }}" class="mb-4">
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
                            <h6 class="m-0 font-weight-bold">Товары по категориям</h6>
                            <x-export-buttons page="products" report="products_by_category" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="chart-pie">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Товары по брендам</h6>
                            <x-export-buttons page="products" report="products_by_brand" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="chart-pie">
                                <canvas id="brandChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Топ 10 самых продаваемых товаров</h6>
                            <x-export-buttons page="products" report="top_selling_products" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Товар</th>
                                            <th>Продано (шт.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topSellingProducts as $product)
                                        <tr>
                                            <td>{{ $product->product->name ?? 'Не указано' }}</td>
                                            <td>{{ $product->total_quantity }}</td>
                                        </tr>
                                        @endforeach
                                        @if(count($topSellingProducts) == 0)
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
                            <h6 class="m-0 font-weight-bold">Товары с низким запасом</h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-danger">Требуют пополнения</span>
                                <x-export-buttons page="products" report="low_stock_products" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Товар</th>
                                            <th>Остаток</th>
                                            <th>Использовано за период</th>
                                            <th>Приоритет</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>
                                                <span class="badge w-50 {{ $product->quantity <= 2 ? 'text-bg-danger' : 'text-bg-warning' }}">{{ $product->quantity }}</span>
                                            </td>
                                            <td>{{ $product->usage_frequency }} шт.</td>
                                            <td>
                                                @if($product->usage_frequency > 10)
                                                    <span class="badge text-bg-danger">Высокий</span>
                                                @elseif($product->usage_frequency > 5)
                                                    <span class="badge text-bg-warning">Средний</span>
                                                @else
                                                    <span class="badge badge-info">Низкий</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @if(count($lowStockProducts) == 0)
                                        <tr>
                                            <td colspan="4" class="text-center">Нет товаров с низким запасом</td>
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
    // Товары по категориям (круговая диаграмма)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryLabels = [
        @foreach($productsByCategory as $item)
            "{{ $item->category_name }}",
        @endforeach
    ];
    const categoryData = [
        @foreach($productsByCategory as $item)
            {{ $item->total }},
        @endforeach
    ];
    
    // Генерация цветов для категорий
    const categoryColors = generateRandomColors(categoryLabels.length);
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: categoryColors,
                hoverBackgroundColor: categoryColors,
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

    // Товары по брендам (круговая диаграмма)
    const brandCtx = document.getElementById('brandChart').getContext('2d');
    const brandLabels = [
        @foreach($productsByBrand as $item)
            "{{ $item->brand_name }}",
        @endforeach
    ];
    const brandData = [
        @foreach($productsByBrand as $item)
            {{ $item->total }},
        @endforeach
    ];
    
    // Генерация цветов для брендов
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