@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="h3 m-0 font-weight-bold">Статистика сотрудников</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.statistics.employees') }}" class="mb-4">
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
                            <h6 class="m-0 font-weight-bold">Сотрудники по специализациям</h6>
                            <x-export-buttons page="employees" report="employees_by_specialization" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="chart-pie">
                                <canvas id="specializationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Сотрудники по стажу</h6>
                            <x-export-buttons page="employees" report="employees_by_seniority" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="seniorityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Топ 10 механиков по количеству выполненных работ</h6>
                            <x-export-buttons page="employees" report="top_mechanics" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Механик</th>
                                            <th>Спец.</th>
                                            <th>Квал.</th>
                                            <th>Кол-во услуг</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topEmployeesByServices as $employee)
                                        <tr>
                                            <td>{{ $employee->user->name ?? 'Не указано' }}</td>
                                            <td>{{ $employee->specialization->code ?? '-' }}</td>
                                            <td>{{ $employee->qualification->code ?? '-' }}</td>
                                            <td>{{ $employee->services_count }}</td>
                                        </tr>
                                        @endforeach
                                        @if(count($topEmployeesByServices) == 0)
                                        <tr>
                                            <td colspan="4" class="text-center">Нет данных</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Топ 10 менеджеров по количеству заказов</h6>
                            <x-export-buttons page="employees" report="top_managers" :filters="['start_date' => $startDate, 'end_date' => $endDate]" />
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Менеджер</th>
                                            <th>Спец.</th>
                                            <th>Квал.</th>
                                            <th>Заказов</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topManagersByOrders as $manager)
                                        <tr>
                                            <td>{{ $manager->user->name ?? 'Не указано' }}</td>
                                            <td>{{ $manager->specialization->code ?? '-' }}</td>
                                            <td>{{ $manager->qualification->code ?? '-' }}</td>
                                            <td>{{ $manager->orders_count }}</td>
                                        </tr>
                                        @endforeach
                                        @if(count($topManagersByOrders) == 0)
                                        <tr>
                                            <td colspan="4" class="text-center">Нет данных</td>
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
    // Сотрудники по специализациям (круговая диаграмма)
    const specializationCtx = document.getElementById('specializationChart').getContext('2d');
    const specializationLabels = [
        @foreach($employeesBySpecialization as $item)
            "{{ $item->specialization_name }}",
        @endforeach
    ];
    const specializationData = [
        @foreach($employeesBySpecialization as $item)
            {{ $item->total }},
        @endforeach
    ];
    
    // Генерация цветов для диаграммы
    const specializationColors = generateRandomColors(specializationLabels.length);
    
    new Chart(specializationCtx, {
        type: 'doughnut',
        data: {
            labels: specializationLabels,
            datasets: [{
                data: specializationData,
                backgroundColor: specializationColors,
                hoverBackgroundColor: specializationColors,
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

    // Сотрудники по стажу (столбчатая диаграмма)
    const seniorityCtx = document.getElementById('seniorityChart').getContext('2d');
    const seniorityLabels = [
        @foreach($employeesBySeniority as $item)
            "{{ $item->seniority_years }} {{ $item->seniority_years == 1 ? 'год' : ($item->seniority_years > 1 && $item->seniority_years < 5 ? 'года' : 'лет') }}",
        @endforeach
    ];
    const seniorityData = [
        @foreach($employeesBySeniority as $item)
            {{ $item->total }},
        @endforeach
    ];
    
    new Chart(seniorityCtx, {
        type: 'bar',
        data: {
            labels: seniorityLabels,
            datasets: [{
                label: "Количество сотрудников",
                backgroundColor: "#36b9cc",
                hoverBackgroundColor: "#2c9faf",
                borderColor: "#36b9cc",
                data: seniorityData,
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