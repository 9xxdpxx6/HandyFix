@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать роль</h1>
        <form action="{{ route('dashboard.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Название роли</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
            </div>

            <h4>Пермишены</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Модуль</th>
                    <th>Create</th>
                    <th>Read</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($permissions as $module => $modulePermissions)
                    <tr>
                        <td>{{ ucfirst($module) }}</td>
                        @foreach (['create', 'read', 'update', 'delete'] as $action)
                            @if ($module === 'statistics' && $action !== 'read')
                                <!-- Пропускаем недоступные действия для statistics -->
                                <td></td>
                            @else
                                <td>
                                    <input type="checkbox" name="permissions[]" value="{{ $action }}.{{ $module }}"
                                        {{ $role->hasPermissionTo($action . '.' . $module) ? 'checked' : '' }}>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
