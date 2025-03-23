@extends('layouts.app')

@section('title', 'Редактировать клиента')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Редактировать клиента</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.customers.update', $customer->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PATCH')

                <input type="hidden" name="user_id" value="{{ $customer->user->id }}">

                <!-- Поля пользователя -->
                <div class="col-md-6">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customer->user->name ?? '') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->user->email ?? '') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->user->phone ?? '') }}">
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Дополнительная информация о клиенте -->
                <div class="col-md-12">
                    <label for="info" class="form-label">Дополнительная информация</label>
                    <textarea name="info" id="info" class="form-control @error('info') is-invalid @enderror" rows="3">{{ old('info', $customer->info) }}</textarea>
                    @error('info')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Баллы лояльности -->
                <div class="col-md-6">
                    <label for="loyalty_points" class="form-label">Бонусные баллы</label>
                    <input type="number" name="loyalty_points" id="loyalty_points" class="form-control @error('loyalty_points') is-invalid @enderror" value="{{ old('loyalty_points', $customer->loyalty_points) }}">
                    @error('loyalty_points')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Уровень лояльности -->
                <div class="col-md-6">
                    <label for="loyalty_level_id" class="form-label">Уровень лояльности</label>
                    <select name="loyalty_level_id" id="loyalty_level_id" class="form-select @error('loyalty_level_id') is-invalid @enderror">
                        <option value="" {{ old('loyalty_level_id', $customer->loyalty_level_id) ? '' : 'selected' }}>Выберите уровень лояльности</option>
                        @foreach ($loyaltyLevels as $level)
                            <option value="{{ $level->id }}" {{ old('loyalty_level_id', $customer->loyalty_level_id) == $level->id ? 'selected' : '' }}>
                                {{ $level->name }} ({{ $level->min_points }}+ баллов)
                            </option>
                        @endforeach
                    </select>
                    @error('loyalty_level_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Кнопки отправки -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Обновить клиента</button>
                </div>
            </form>
        </div>
    </div>
@endsection
