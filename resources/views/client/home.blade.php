@extends('layouts.client')

@section('title', 'Главная')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Профессиональный автосервис HandyFix</h1>
            <p class="lead mb-5">Качественный ремонт и обслуживание вашего автомобиля</p>
            <div>
                <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg me-2">Наши услуги</a>
                <div class="mt-4">
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Зарегистрироваться</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Почему выбирают нас</h2>
                <p class="text-muted">Мы делаем всё, чтобы ваш автомобиль был в отличном состоянии</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-tools text-primary fa-2x"></i>
                        </div>
                        <h4>Современное оборудование</h4>
                        <p class="text-muted">Мы используем только современное оборудование для диагностики и ремонта автомобилей.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-user-tie text-primary fa-2x"></i>
                        </div>
                        <h4>Опытные специалисты</h4>
                        <p class="text-muted">Наши мастера имеют высокую квалификацию и многолетний опыт работы.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-thumbs-up text-primary fa-2x"></i>
                        </div>
                        <h4>Гарантия качества</h4>
                        <p class="text-muted">Мы предоставляем гарантию на все виды работ и используемые запчасти.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Наши услуги</h2>
                <p class="text-muted">Полный спектр услуг для вашего автомобиля</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <i class="fas fa-car-battery fa-3x text-primary mb-3"></i>
                            <h4 class="card-title">Диагностика</h4>
                            <p class="card-text">Компьютерная диагностика, выявление неисправностей, проверка систем автомобиля.</p>
                            <a href="{{ route('services.index') }}" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <i class="fas fa-oil-can fa-3x text-primary mb-3"></i>
                            <h4 class="card-title">Техническое обслуживание</h4>
                            <p class="card-text">Регулярное ТО, замена масла и фильтров, проверка тормозной системы.</p>
                            <a href="{{ route('services.index') }}" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <i class="fas fa-cogs fa-3x text-primary mb-3"></i>
                            <h4 class="card-title">Ремонт</h4>
                            <p class="card-text">Ремонт двигателя, ходовой части, коробки передач и других систем автомобиля.</p>
                            <a href="{{ route('services.index') }}" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('services.index') }}" class="btn btn-primary">Все услуги</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="fw-bold mb-4">Готовы записаться на обслуживание?</h2>
                    @guest
                    <p class="lead mb-4">Зарегистрируйтесь на нашем сайте и получите возможность онлайн-записи на сервис</p>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Зарегистрироваться</a>
                    @else
                    <p class="lead mb-4">Запишитесь на сервис онлайн прямо сейчас</p>
                    <a href="{{ route('order.create') }}" class="btn btn-primary btn-lg">Записаться на сервис</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>
@endsection
