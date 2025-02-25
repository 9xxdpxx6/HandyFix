@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Детали категории</h1>

        <p><strong>ID:</strong> {{ $category->id }}</p>
        <p><strong>Название:</strong> {{ $category->name }}</p>
        <p><strong>Иконка:</strong> <img src="{{ $category->icon }}" alt="{{ $category->name }}" style="max-width: 100px;"></p>
        <p><strong>Описание:</strong> {{ $category->description }}</p>
        <p><strong>Родительская категория:</strong> {{ $category->parent?->name ?? 'Нет родителя' }}</p>

        <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
