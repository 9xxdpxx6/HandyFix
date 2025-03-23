@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Информация о роли</h1>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $role->id }}</td>
            </tr>
            <tr>
                <th>Название</th>
                <td>{{ $role->name }}</td>
            </tr>
            <tr>
                <th>Разрешения</th>
                <td>
                    @if ($role->permissions->isNotEmpty())
                        {{ $role->permissions->pluck('name')->join(', ') }}
                    @else
                        Нет разрешений
                    @endif
                </td>
            </tr>
        </table>
        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary">Назад к списку</a>
        <a href="{{ route('dashboard.roles.edit', $role) }}" class="btn btn-warning">Редактировать</a>
    </div>
@endsection
