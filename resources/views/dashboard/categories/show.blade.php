@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Детали категории</h1>

        <p><strong>ID:</strong> {{ $category->id }}</p>
        <p><strong>Название:</strong> {{ $category->name }}</p>
        <p class="d-flex align-items-center">
            <strong>Иконка:</strong>
            @if($category->icon)
                <div class="display-2">
                    <div class="bg-light rounded-2 p-1 icon-square">
                        <i class="hf-icon {{ $category->icon }}"></i>
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
        <p><strong>Описание:</strong> {{ $category->description }}</p>
        <p><strong>Родительская категория:</strong> {{ $category->parent?->name ?? 'Нет родителя' }}</p>

        <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
