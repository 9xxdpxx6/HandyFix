@extends('layouts.client')

@section('title', 'Оплата заказа №' . $order->id)

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Мои заказы</a></li>
                <li class="breadcrumb-item"><a href="{{ route('order.show', $order) }}">Заказ №{{ $order->id }}</a></li>
                <li class="breadcrumb-item active">Оплата</li>
            </ol>
        </nav>
        
        <h1 class="mb-4">Оплата заказа №{{ $order->id }}</h1>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Оплата через Систему Быстрых Платежей (СБП)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <div class="mb-3">
                                    <h5>Отсканируйте QR-код</h5>
                                    <p class="text-muted">Используйте камеру телефона или приложение вашего банка</p>
                                    
                                    <div class="qr-code-container border p-2 d-inline-block mt-3">
                                        @if(Storage::exists('public/qr.jpg'))
                                            <img src="{{ Storage::url('public/qr.jpg') }}" alt="QR-код для оплаты" class="img-fluid" />
                                        @else
                                            <img src="https://via.placeholder.com/250x250?text=QR+СБП" alt="QR-код для оплаты" class="img-fluid" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>Детали платежа</h5>
                                    <hr>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-6">Номер заказа:</dt>
                                        <dd class="col-sm-6">{{ $order->id }}</dd>
                                        
                                        <dt class="col-sm-6">Сумма к оплате:</dt>
                                        <dd class="col-sm-6 fw-bold text-primary">{{ number_format($totalAmount, 0, ',', ' ') }} ₽</dd>
                                        
                                        <dt class="col-sm-6">Получатель:</dt>
                                        <dd class="col-sm-6">ООО "HandyFix"</dd>
                                        
                                        <dt class="col-sm-6">Назначение:</dt>
                                        <dd class="col-sm-6">Оплата заказа №{{ $order->id }}</dd>
                                    </dl>
                                </div>
                                
                                <div class="alert alert-info">
                                    <h6 class="alert-heading mb-2"><i class="fas fa-info-circle me-1"></i> Как оплатить</h6>
                                    <ol class="mb-0 ps-3">
                                        <li>Отсканируйте QR-код через приложение банка</li>
                                        <li>Проверьте сумму и детали платежа</li>
                                        <li>Подтвердите операцию</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <!-- В реальном проекте этот блок будет обновляться через AJAX -->
                            <p>После оплаты вы будете автоматически перенаправлены на страницу заказа.</p>
                            
                            <form action="{{ route('order.pay', $order) }}" method="POST" class="mt-3 d-inline-block">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $paymentId }}">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle me-1"></i> Я оплатил заказ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Сводка заказа</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Автомобиль:</span>
                                    <span>{{ $order->vehicle->model->brand->name }} {{ $order->vehicle->model->name }}</span>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Гос. номер:</span>
                                    <span>{{ $order->vehicle->license_plate }}</span>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Количество услуг:</span>
                                    <span>{{ $order->serviceEntries->count() }}</span>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Итого к оплате:</span>
                                    <span class="fw-bold text-primary">{{ number_format($totalAmount, 0, ',', ' ') }} ₽</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Поддержка</h5>
                    </div>
                    <div class="card-body">
                        <p>Возникли проблемы с оплатой? Свяжитесь с нами:</p>
                        <p><i class="fas fa-phone me-2"></i> +7 (495) 123-45-67</p>
                        <p class="mb-0"><i class="fas fa-envelope me-2"></i> payments@handyfix.ru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // В реальном проекте здесь будет код для проверки статуса платежа
    // и автоматического перенаправления после успешной оплаты
    /*
    function checkPaymentStatus() {
        fetch('/api/payments/{{ $paymentId }}/status')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'completed') {
                    window.location.href = '{{ route('order.show', $order) }}';
                }
            });
    }
    
    // Проверяем статус платежа каждые 5 секунд
    const intervalId = setInterval(checkPaymentStatus, 5000);
    
    // Очищаем интервал при уходе со страницы
    window.addEventListener('beforeunload', function() {
        clearInterval(intervalId);
    });
    */
</script>
@endpush 