@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список ролей</h1>
        <a href="{{ route('dashboard.roles.create') }}" class="btn btn-primary mb-3">Добавить роль</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Разрешения</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        @if ($role->permissions->isNotEmpty())
                            {{ $role->permissions->pluck('name')->join(', ') }}
                        @else
                            Нет пермишенов
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dashboard.roles.show', $role) }}" class="btn btn-info btn-sm">Просмотр</a>
                        <a href="{{ route('dashboard.roles.edit', $role) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('dashboard.roles.destroy', $role) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $roles->links() }}
    </div>
@endsection
