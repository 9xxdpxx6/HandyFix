@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать квалификацию</h1>
        <form action="{{ route('dashboard.qualifications.update', $qualification) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $qualification->name }}" required>
            </div>
            <div class="mb-3">
                <label for="min_seniority" class="form-label">Минимальный стаж</label>
                <input type="number" name="min_seniority" id="min_seniority" class="form-control" value="{{ $qualification->min_seniority }}" min="0" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Код (3 символа)</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ $qualification->code }}" maxlength="3" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control">{{ $qualification->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
