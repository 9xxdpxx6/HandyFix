@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация о роли: {{ $role->name }}</h5>
                <div>
                    @can('update', $role)
                    <a href="{{ route('dashboard.roles.edit', $role) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $role)
                    <form action="{{ route('dashboard.roles.destroy', $role) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            
            <div class="card-body">
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
                                <ul>
                                @foreach($role->permissions->sortBy('name') as $permission)
                                    <li>{{ $permission->name }}</li>
                                @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Нет разрешений</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
