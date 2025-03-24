@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование категории</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.categories.update', $category) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" required>
            </div>

            <div class="mb-3">
                @include('components.icon-picker', ['selectedIcon' => $category->icon])
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control">{{ $category->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="parent_id" class="form-label">Родительская категория</label>
                <select name="parent_id" id="parent_id" class="form-select">
                    @foreach ($parents as $id => $name)
                        <option value="{{ $id }}" {{ $category->parent_id === $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
