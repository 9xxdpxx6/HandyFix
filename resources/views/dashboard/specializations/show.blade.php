@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация о специальности: {{ $specialization->name }}</h5>
                <div>
                    @can('update', $specialization)
                    <a href="{{ route('dashboard.specializations.edit', $specialization) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $specialization)
                    <form action="{{ route('dashboard.specializations.destroy', $specialization) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.specializations.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            
            <div class="card-body">
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
            </div>
        </div>
    </div>
@endsection
