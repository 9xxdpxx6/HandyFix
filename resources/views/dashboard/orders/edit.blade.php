@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            Редактирование заказа
        </div>
        <div class="card-body">
            <form id="orderForm" action="{{ route('dashboard.orders.update', $order) }}" method="POST">
                @csrf
                @method('PATCH')

                <input type="hidden" name="products[]" id="products">
                <input type="hidden" name="services[]" id="services">

                <div class="row">
                    <!-- Выбор покупателя -->
                    <div class="col-12 col-lg-6 form-group mb-3">
                        <label for="customer_id">Покупатель</label>
                        <select name="customer_id" id="customer_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text" required>
                            <option value="">Выберите покупателя</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->user->name }} {{ $customer->user->phone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Выбор статуса -->
                    <div class="col-12 col-lg-6 form-group mb-3">
                        <label for="status_id">Статус</label>
                        <select name="status_id" id="status_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text" required>
                            <option value="">Выберите статус</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Выбор автомобиля -->
                    <div class="col-12 col-lg-6 form-group mb-3">
                        <label for="vehicle_id">Автомобиль</label>
                        <select name="vehicle_id" id="vehicle_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text" required>
                            <option value="">Выберите автомобиль</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ $order->vehicle_id == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->model->brand->name }} {{ $vehicle->model->name }} ({{ $vehicle->year }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-xxl-6 form-group mb-3">
                        <x-searchable-list
                            entity-type="product"
                            search-url="{{ route('api.products.search') }}"
                            entity-type-label="товаров"
                            :initial-items="$order->purchases->map(function($purchase) { return ['id' => $purchase->product->id, 'name' => $purchase->product->name, 'sku' => $purchase->product->sku, 'price' => $purchase->product->price, 'quantity' => $purchase->quantity]; })->toJson()"
                        ></x-searchable-list>
                        <div id="initial-items-product" data-items="{{ $order->purchases->map(function($purchase) { return ['id' => $purchase->product->id, 'name' => $purchase->product->name, 'sku' => $purchase->product->sku, 'price' => $purchase->product->price, 'quantity' => $purchase->quantity]; })->toJson() }}"></div>
                    </div>

                    <div class="col-12 col-xxl-6 form-group mb-3">
                        <x-searchable-list
                            entity-type="service"
                            search-url="{{ route('api.services.search') }}"
                            entity-type-label="услуг"
                            :initial-items="$order->serviceEntries->map(function($serviceEntry) { return ['id' => $serviceEntry->service->id, 'name' => $serviceEntry->service->name, 'code' => $serviceEntry->service->code, 'price' => $serviceEntry->service->price, 'quantity' => $serviceEntry->quantity]; })->toJson()"
                        ></x-searchable-list>
                        <div id="initial-items-service" data-items="{{ $order->serviceEntries->map(function($serviceEntry) { return ['id' => $serviceEntry->service->id, 'name' => $serviceEntry->service->name, 'code' => $serviceEntry->service->code, 'price' => $serviceEntry->service->price, 'quantity' => $serviceEntry->quantity]; })->toJson() }}"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="mb-3">
                            <div class="input-group">
                                <label class="input-group-text" for="total">ИТОГО</label>
                                <input type="text" name="total" id="total" class="form-control text-end" value="{{ $order->total }}" readonly>
                                <span class="input-group-text">₽</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="comment">Комментарий</label>
                    <textarea name="comment" class="form-control">{{ $order->comment }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="note">Примечание</label>
                    <textarea name="note" class="form-control">{{ $order->note }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary mb-3">Обновить</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('orderForm');

            form.addEventListener('submit', function (event) {
                // Получаем данные из компонентов
                const productComponent = window.componentInstances?.product;
                const serviceComponent = window.componentInstances?.service;

                // Форматируем данные в JSON
                const products = productComponent?.selectedItemsContainer.getAttribute('data-items') || '[]';
                const services = serviceComponent?.selectedItemsContainer.getAttribute('data-items') || '[]';

                // Заполняем скрытые поля массивами
                document.getElementById('products').value = products;
                document.getElementById('services').value = services;
            });

            // Функция для обновления общей суммы
            function updateTotalAmount() {
                const productTotalElement = document.getElementById('total-price-product');
                const serviceTotalElement = document.getElementById('total-price-service');
                const totalInput = document.getElementById('total');

                if (!totalInput) {
                    console.error('Элемент #total не найден');
                    return;
                }

                // Получаем значения из элементов итоговой цены
                const productTotal = parseFloat(productTotalElement?.textContent.replace('Итого: ', '').replace(' ₽', '')) || 0;
                const serviceTotal = parseFloat(serviceTotalElement?.textContent.replace('Итого: ', '').replace(' ₽', '')) || 0;

                // Считаем общую сумму
                const totalAmount = productTotal + serviceTotal;

                // Обновляем значение в поле суммы
                totalInput.value = totalAmount.toFixed(2);
            }

            // Наблюдатель за изменениями в контейнерах
            const observer = new MutationObserver((mutationsList) => {
                mutationsList.forEach(mutation => {
                    if (mutation.type === 'childList') {
                        // Проверяем, появились ли элементы #total-price-product или #total-price-service
                        const productTotalElement = document.getElementById('total-price-product');
                        const serviceTotalElement = document.getElementById('total-price-service');

                        if (productTotalElement || serviceTotalElement) {
                            updateTotalAmount();
                        }
                    }
                });
            });

            // Начинаем наблюдение за родительскими контейнерами
            const productContainer = document.getElementById('selected-items-product');
            const serviceContainer = document.getElementById('selected-items-service');

            if (productContainer) {
                observer.observe(productContainer, { childList: true, subtree: true });
            }

            if (serviceContainer) {
                observer.observe(serviceContainer, { childList: true, subtree: true });
            }

            // Инициализация начальной суммы
            updateTotalAmount();
        });
    </script>
@endpush
