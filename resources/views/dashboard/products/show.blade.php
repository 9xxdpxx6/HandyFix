@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Просмотр товара: {{ $product->name }}</h5>
                <div>
                    <x-permission requires="update.products">
                        <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-warning btn-sm">
                            <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                        </a>
                    </x-permission>
                    
                    <x-permission requires="delete.products">
                        <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                <x-icon icon="trash-can" class="icon-20"/> Удалить
                            </button>
                        </form>
                    </x-permission>
                    
                    <a href="{{ route('dashboard.products.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if ($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded img-fluid mb-3">
                        @else
                            <div class="display-2">
                                <div class="bg-light rounded-2 p-1 icon-square">
                                    <i class="hf-icon hf-no-image"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h5>Информация о товаре</h5>
                        <p><strong>Артикул:</strong> {{ $product->sku ?? 'Нет артикула' }}</p>
                        <p><strong>Описание:</strong> {{ $product->description ?? 'Нет описания' }}</p>
                        <p><strong>Цена:</strong> {{ number_format($product->price, 2, ',', ' ') }} ₽</p>
                        <p><strong>Количество:</strong> {{ $product->quantity }}</p>
                        <p><strong>Категория:</strong> {{ $product->category->name }}</p>
                        <p><strong>Бренд:</strong> {{ $product->brand->name }}</p>
                    </div>
                </div>
                
                @if($product->getPriceHistory()->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Динамика изменения цен</h5>
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
                            Отображено {{ $product->getPriceHistory()->count() }} из {{ $product->productPrices()->count() }} записей
                        </div>
                    </div>
                </div>
                @endif
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
    @if($product->getPriceHistory()->count() > 0)
    // Преобразуем данные
    const dates = [
        @foreach($product->getPriceHistory() as $price)
            '{{ \Carbon\Carbon::parse($price->created_at)->format('d.m.Y') }}',
        @endforeach
    ];
    
    const prices = [
        @foreach($product->getPriceHistory() as $price)
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
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
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
