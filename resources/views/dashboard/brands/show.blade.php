@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация о бренде: {{ $brand->name }}</h5>
                <div>
                    <x-permission requires="update.brands">
                        <a href="{{ route('dashboard.brands.edit', $brand) }}" class="btn btn-warning btn-sm">
                            <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                        </a>
                    </x-permission>
                    
                    <x-permission requires="delete.brands">
                        <form action="{{ route('dashboard.brands.destroy', $brand) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                <x-icon icon="trash-can" class="icon-20"/> Удалить
                            </button>
                        </form>
                    </x-permission>
                    
                    <a href="{{ route('dashboard.brands.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $brand->id }}</p>
                        <p><strong>Название:</strong> {{ $brand->name }}</p>
                        <p><strong>Описание:</strong> {{ $brand->description ?: 'Нет описания' }}</p>
                        <p><strong>Оригинальный:</strong> {{ $brand->is_original ? 'Да' : 'Нет' }}</p>
                        <p><strong>Страна регистрации:</strong> {{ $brand->registrationCountry?->name ?? 'Не указано' }}</p>
                        <p><strong>Страна производства:</strong> {{ $brand->productionCountry?->name ?? 'Не указано' }}</p>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        @if($brand->icon)
                            <div class="display-2">
                                <div class="bg-light rounded-2 p-3 icon-square">
                                    <i class="hf-icon {{ $brand->icon }}"></i>
                                </div>
                            </div>
                        @else
                            <div class="display-2">
                                <div class="bg-light rounded-2 p-3 icon-square">
                                    <i class="hf-icon hf-no-image"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
