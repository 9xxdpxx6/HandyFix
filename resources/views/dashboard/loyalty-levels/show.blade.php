@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Детали бонусной программы</h1>

        <p><strong>ID:</strong> {{ $loyaltyLevel->id }}</p>
        <p><strong>Название:</strong> {{ $loyaltyLevel->name }}</p>
        <p><strong>Минимальные баллы:</strong> {{ $loyaltyLevel->min_points }}</p>
        <p><strong>Скидка (%):</strong> {{ $loyaltyLevel->discount }}</p>

        <a href="{{ route('dashboard.loyalty-levels.edit', $loyaltyLevel) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('dashboard.loyalty-levels.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
