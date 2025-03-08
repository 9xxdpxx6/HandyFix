@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Квалификация: {{ $qualification->name }}</h1>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $qualification->id }}</td>
            </tr>
            <tr>
                <th>Название</th>
                <td>{{ $qualification->name }}</td>
            </tr>
            <tr>
                <th>Минимальный стаж</th>
                <td>{{ $qualification->min_seniority }}</td>
            </tr>
            <tr>
                <th>Код</th>
                <td>{{ $qualification->code }}</td>
            </tr>
            <tr>
                <th>Описание</th>
                <td>{{ $qualification->description ?? 'Нет описания' }}</td>
            </tr>
        </table>
        <a href="{{ route('dashboard.qualifications.index') }}" class="btn btn-secondary">Назад к списку</a>
        <a href="{{ route('dashboard.qualifications.edit', $qualification) }}" class="btn btn-warning">Редактировать</a>
    </div>
@endsection
