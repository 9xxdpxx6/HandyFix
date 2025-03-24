@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Специализация: {{ $specialization->name }}</h1>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $specialization->id }}</td>
            </tr>
            <tr>
                <th>Название</th>
                <td>{{ $specialization->name }}</td>
            </tr>
            <tr>
                <th>Код</th>
                <td>{{ $specialization->code }}</td>
            </tr>
            <tr>
                <th>Описание</th>
                <td>{{ $specialization->description ?? 'Нет описания' }}</td>
            </tr>
        </table>
        <a href="{{ route('dashboard.specializations.index') }}" class="btn btn-secondary">Назад к списку</a>
        <a href="{{ route('dashboard.specializations.edit', $specialization) }}" class="btn btn-warning">Редактировать</a>
    </div>
@endsection
