@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Детали бренда</h1>

        <p><strong>ID:</strong> {{ $brand->id }}</p>
        <p><strong>Название:</strong> {{ $brand->name }}</p>
        <p class="d-flex align-items-center">
            <strong>Иконка:</strong>
            @if($brand->icon)
                <div class="display-2">
                    <div class="bg-light rounded-2 p-1 icon-square">
                        <i class="hf-icon {{ $brand->icon }}"></i>
                    </div>
                </div>
            @else
                <div class="display-2">
                    <div class="bg-light rounded-2 p-1 icon-square">
                        <i class="hf-icon hf-no-image"></i>
                    </div>
                </div>
            @endif
        </p>
        <p><strong>Описание:</strong> {{ $brand->description }}</p>
        <p><strong>Оригинальный:</strong> {{ $brand->is_original ? 'Да' : 'Нет' }}</p>
        <p><strong>Страна регистрации:</strong> {{ $brand->registrationCountry?->name ?? 'Не указано' }}</p>
        <p><strong>Страна производства:</strong> {{ $brand->productionCountry?->name ?? 'Не указано' }}</p>

        <a href="{{ route('dashboard.brands.edit', $brand) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('dashboard.brands.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
