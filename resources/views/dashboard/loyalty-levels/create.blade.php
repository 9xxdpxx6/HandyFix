@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создание новой бонусной программы</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.loyalty-levels.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="min_points" class="form-label">Минимальные баллы</label>
                <input type="number" name="min_points" id="min_points" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Скидка (%)</label>
                <input type="number" step="0.01" name="discount" id="discount" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
