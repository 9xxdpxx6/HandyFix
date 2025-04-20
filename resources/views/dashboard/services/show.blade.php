@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Информация об услуге</h3>
            <div>
                <a href="{{ route('dashboard.services.edit', $service) }}" class="btn btn-warning btn-sm">
                    <x-icon icon="pencil-square" class="icon-20"/>
                    Редактировать
                </a>
                <a href="{{ route('dashboard.services.index') }}" class="btn btn-secondary btn-sm">
                    <x-icon icon="arrow-left" class="icon-20"/>
                    Назад
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Основная информация</h4>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">ID:</th>
                            <td>{{ $service->id }}</td>
                        </tr>
                        <tr>
                            <th>Название:</th>
                            <td>{{ $service->name }}</td>
                        </tr>
                        <tr>
                            <th>Тип услуги:</th>
                            <td>{{ $service->serviceType->name }}</td>
                        </tr>
                        <tr>
                            <th>Цена:</th>
                            <td>{{ number_format($service->price, 2, '.', ' ') }} ₽</td>
                        </tr>
                        <tr>
                            <th>Описание:</th>
                            <td>{{ $service->description ?: 'Нет описания' }}</td>
                        </tr>
                        <tr>
                            <th>Создано:</th>
                            <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Обновлено:</th>
                            <td>{{ \Carbon\Carbon::parse($service->updated_at)->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h4>Статистика использования</h4>
                    <table class="table mb-4">
                        <tr>
                            <th style="width: 200px;">Количество записей:</th>
                            <td>{{ $service->serviceEntries()->count() }}</td>
                        </tr>
                    </table>
                    
                    @if($service->getPriceHistory()->count() > 0)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Динамика изменения цен</h4>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switchPriceHistory" 
                                {{ $showAllPrices ? 'checked' : '' }}
                                onchange="togglePriceHistory(this.checked)">
                            <label class="form-check-label" for="switchPriceHistory">
                                Показать все
                            </label>
                        </div>
                    </div>
                    <div>
                        <canvas id="priceHistoryChart" style="width: 100%; height: 300px;"></canvas>
                    </div>
                    <div class="text-muted mt-2 small">
                        Отображено {{ $service->getPriceHistory()->count() }} из {{ $service->servicePrices()->count() }} записей
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Функция для переключения режима отображения истории цен
function togglePriceHistory(showAll) {
    const url = new URL(window.location.href);
    if (showAll) {
        url.searchParams.set('show_all_prices', '1');
    } else {
        url.searchParams.delete('show_all_prices');
    }
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    @if($service->getPriceHistory()->count() > 0)
    // Преобразуем данные
    const dates = [
        @foreach($service->getPriceHistory() as $price)
            '{{ \Carbon\Carbon::parse($price->created_at)->format('d.m.Y') }}',
        @endforeach
    ];
    
    const prices = [
        @foreach($service->getPriceHistory() as $price)
            {{ $price->price }},
        @endforeach
    ];
    
    // Создаем контекст для графика
    const ctx = document.getElementById('priceHistoryChart').getContext('2d');
    
    // Инициализируем график
    const priceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Цена (₽)',
                data: prices,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Цена (₽)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Дата'
                    }
                }
            },
            plugins: {
                title: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Цена: ' + parseFloat(context.raw).toFixed(2) + ' ₽';
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush 