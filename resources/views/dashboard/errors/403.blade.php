@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <span class="text-danger" style="font-size: 80px;">
                            <i class="icon icon-ban"></i>
                        </span>
                    </div>
                    <h1 class="display-4 fw-bold text-danger mb-3">403</h1>
                    <h2 class="mb-4">Доступ запрещен</h2>
                    <p class="lead text-muted mb-5">У вас нет прав для доступа к запрашиваемой странице.</p>
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