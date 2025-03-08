@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список специализаций</h1>
        <a href="{{ route('dashboard.specializations.create') }}" class="btn btn-primary mb-3">Добавить специализацию</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Код</th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($specializations as $specialization)
                <tr>
                    <td>{{ $specialization->id }}</td>
                    <td>{{ $specialization->name }}</td>
                    <td>{{ $specialization->code }}</td>
                    <td>{{ $specialization->description ?? 'Нет описания' }}</td>
                    <td>
                        <a href="{{ route('dashboard.specializations.show', $specialization) }}" class="btn btn-info btn-sm">Просмотр</a>
                        <a href="{{ route('dashboard.specializations.edit', $specialization) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('dashboard.specializations.destroy', $specialization) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $specializations->links() }}
    </div>
@endsection
