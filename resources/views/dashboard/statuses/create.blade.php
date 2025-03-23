@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создание нового статуса</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.statuses.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Цвет</label>
                <input type="color" name="color" id="color" class="form-control w-25" value="#ffffff" style="height: 70px;">
            </div>

            <div class="mb-3">
                <label for="is_closing" class="form-label">Закрывающий статус</label>
                <input type="checkbox" name="is_closing" id="is_closing" class="form-check-input">
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
