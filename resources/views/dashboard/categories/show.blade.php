@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация о категории: {{ $category->name }}</h5>
                <div>
                    <x-permission requires="update.categories">
                        <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                            <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                        </a>
                    </x-permission>
                    
                    <x-permission requires="delete.categories">
                        <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                <x-icon icon="trash-can" class="icon-20"/> Удалить
                            </button>
                        </form>
                    </x-permission>
                    
                    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $category->id }}</p>
                <p><strong>Название:</strong> {{ $category->name }}</p>
                <p><strong>Родительская категория:</strong> {{ $category->parent ? $category->parent->name : 'Нет' }}</p>
                <p><strong>Описание:</strong> {{ $category->description ?: 'Нет описания' }}</p>
                
                @if($category->icon)
                <p>
                    <strong>Иконка:</strong>
                    <span class="bg-light rounded-2 p-1 icon-square d-inline-block">
                        <i class="hf-icon {{ $category->icon }}"></i>
                    </span>
                </p>
                @endif
                
                @if($category->children->count() > 0)
                <div class="mt-4">
                    <h6>Подкатегории:</h6>
                    <ul>
                        @foreach ($category->children as $child)
                        <li>
                            <a href="{{ route('dashboard.categories.show', $child) }}">{{ $child->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
