@extends('layouts.client')

@section('title', 'Мой профиль')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Мой профиль</h1>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted mb-1">{{ $user->email }}</p>
                        <p class="text-muted">{{ $user->phone }}</p>
                        
                        @if(isset($customer) && $customer && $customer->loyaltyLevel)
                            <div class="mt-3">
                                <div class="badge bg-success p-2">
                                    <i class="fas fa-award me-1"></i>
                                    {{ $customer->loyaltyLevel->name }}
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Баллы лояльности: {{ $customer->loyalty_points }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-car me-1"></i> Мои автомобили
                            </a>
                            <a href="{{ route('order.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-clipboard-list me-1"></i> Мои заказы
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Редактирование профиля</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">ФИО</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="info" class="form-label">Дополнительная информация</label>
                                <textarea id="info" class="form-control @error('info') is-invalid @enderror" name="info" rows="3">{{ old('info', isset($customer) && $customer ? $customer->info : '') }}</textarea>
                                @error('info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Новый пароль</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                <div class="form-text">Оставьте поле пустым, если не хотите менять пароль</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Подтверждение пароля</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Обновить профиль</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 