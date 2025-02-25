@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создание нового бренда</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.brands.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="icon" class="form-label">Иконка (URL)</label>
                <input type="url" name="icon" id="icon" class="form-control">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="is_original" class="form-label">Оригинальный бренд</label>
                <input type="checkbox" name="is_original" id="is_original" class="form-check-input">
            </div>

            <div class="mb-3">
                <label for="registration_country_code" class="form-label">Страна регистрации</label>
                <select name="registration_country_code" id="registration_country_code" class="form-select">
                    <option value="">Не указано</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="production_country_code" class="form-label">Страна производства</label>
                <select name="production_country_code" id="production_country_code" class="form-select">
                    <option value="">Не указано</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
