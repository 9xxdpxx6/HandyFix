@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Бонусные программы</h1>
        <a href="{{ route('dashboard.loyalty-levels.create') }}" class="btn btn-primary mb-3">Добавить новую программу</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Минимальные баллы</th>
                <th>Скидка (%)</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($loyaltyLevels as $level)
                <tr>
                    <td>{{ $level->id }}</td>
                    <td>{{ $level->name }}</td>
                    <td>{{ $level->min_points }}</td>
                    <td>{{ $level->discount }}</td>
                    <td>
                        <a href="{{ route('dashboard.loyalty-levels.show', $level) }}" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="{{ route('dashboard.loyalty-levels.edit', $level) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('dashboard.loyalty-levels.destroy', $level) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $loyaltyLevels->links() }}
    </div>
@endsection
