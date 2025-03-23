@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование бренда</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('dashboard.brands.update', $brand) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Название -->
            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $brand->name }}" required>
            </div>

            <!-- Иконка -->
            <div class="mb-3">
                @include('components.icon-picker', ['selectedIcon' => $brand->icon])
            </div>

            <!-- Описание -->
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control">{{ $brand->description }}</textarea>
            </div>

            <!-- Оригинальный бренд -->
            <div class="mb-3">
                <label for="is_original" class="form-label">Оригинальный бренд</label>
                <input type="checkbox" name="is_original" id="is_original" class="form-check-input" {{ $brand->is_original ? 'checked' : '' }}>
            </div>

            <!-- Страна регистрации -->
            <div class="mb-3">
                <label for="registration_country_code" class="form-label">Страна регистрации</label>
                <select name="registration_country_code" id="registration_country_code" class="form-select">
                    <option value="">Не указано</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" {{ $brand->registration_country_code === $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Страна производства -->
            <div class="mb-3">
                <label for="production_country_code" class="form-label">Страна производства</label>
                <select name="production_country_code" id="production_country_code" class="form-select">
                    <option value="">Не указано</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" {{ $brand->production_country_code === $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
