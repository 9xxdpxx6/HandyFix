@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список статусов</h1>
        <a href="{{ route('dashboard.statuses.create') }}" class="btn btn-primary mb-3">Добавить новый статус</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Цвет</th>
                <th>Закрывающий статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($statuses as $status)
                <tr>
                    <td>{{ $status->id }}</td>
                    <td>{{ $status->name }}</td>
                    <td><span style="background-color: {{ $status->color }}; color: white; padding: 5px;" class="rounded-1">{{ $status->color }}</span></td>
                    <td>{{ $status->is_closing ? 'Да' : 'Нет' }}</td>
                    <td>
                        <a href="{{ route('dashboard.statuses.show', $status) }}" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="{{ route('dashboard.statuses.edit', $status) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('dashboard.statuses.destroy', $status) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $statuses->links() }}
    </div>
@endsection
