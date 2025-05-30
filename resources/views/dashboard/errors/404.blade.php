@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <span class="text-primary" style="font-size: 80px;">
                            <i class="icon icon-magnifying-glass"></i>
                        </span>
                    </div>
                    <h1 class="display-4 fw-bold text-primary mb-3">404</h1>
                    <h2 class="mb-4">Страница не найдена</h2>
                    <p class="lead text-muted mb-5">Запрашиваемая страница не существует или была перемещена.</p>
                    <div>
                        <a href="{{ route('dashboard.home') }}" class="btn btn-primary btn-lg px-4">
                            <i class="icon icon-dashboard me-2"></i> Вернуться на главную
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 