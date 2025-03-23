@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Добавить специализацию</h1>
        <form action="{{ route('dashboard.specializations.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Код (4 символа)</label>
                <input type="text" name="code" id="code" class="form-control" maxlength="4" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
