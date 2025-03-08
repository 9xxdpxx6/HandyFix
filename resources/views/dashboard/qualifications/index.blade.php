@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список квалификаций</h1>
        <a href="{{ route('dashboard.qualifications.create') }}" class="btn btn-primary mb-3">Добавить квалификацию</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Минимальный стаж</th>
                <th>Код</th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($qualifications as $qualification)
                <tr>
                    <td>{{ $qualification->id }}</td>
                    <td>{{ $qualification->name }}</td>
                    <td>{{ $qualification->min_seniority }}</td>
                    <td>{{ $qualification->code }}</td>
                    <td>{{ $qualification->description ?? 'Нет описания' }}</td>
                    <td>
                        <a href="{{ route('dashboard.qualifications.show', $qualification) }}" class="btn btn-info btn-sm">Просмотр</a>
                        <a href="{{ route('dashboard.qualifications.edit', $qualification) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('dashboard.qualifications.destroy', $qualification) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $qualifications->links() }}
    </div>
@endsection
