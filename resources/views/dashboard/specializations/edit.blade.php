@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать специализацию</h1>
        <form action="{{ route('dashboard.specializations.update', $specialization) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $specialization->name }}" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Код (4 символа)</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ $specialization->code }}" maxlength="4" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control">{{ $specialization->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
