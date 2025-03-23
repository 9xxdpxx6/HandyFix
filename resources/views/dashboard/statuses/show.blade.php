@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Детали статуса</h1>

        <p><strong>ID:</strong> {{ $status->id }}</p>
        <p><strong>Название:</strong> {{ $status->name }}</p>
        <p><strong>Цвет:</strong> <span class="rounded-1" style="background-color: {{ $status->color }}; color: white; padding: 5px;">{{ $status->color }}</span></p>
        <p><strong>Закрывающий статус:</strong> {{ $status->is_closing ? 'Да' : 'Нет' }}</p>

        <a href="{{ route('dashboard.statuses.edit', $status) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('dashboard.statuses.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
