@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация о статусе: {{ $status->name }}</h5>
                <div>
                    @can('update', $status)
                    <a href="{{ route('dashboard.statuses.edit', $status) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $status)
                    <form action="{{ route('dashboard.statuses.destroy', $status) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.statuses.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $status->id }}</td>
                    </tr>
                    <tr>
                        <th>Название</th>
                        <td>{{ $status->name }}</td>
                    </tr>
                    <tr>
                        <th>Цвет</th>
                        <td>
                            <span class="badge" style="background-color: {{ $status->color }}">
                                {{ $status->color }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Закрывающий статус</th>
                        <td>{{ $status->is_closing ? 'Да' : 'Нет' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
