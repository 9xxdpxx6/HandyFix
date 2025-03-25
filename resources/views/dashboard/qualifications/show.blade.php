@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация о квалификации: {{ $qualification->name }}</h5>
                <div>
                    @can('update', $qualification)
                    <a href="{{ route('dashboard.qualifications.edit', $qualification) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $qualification)
                    <form action="{{ route('dashboard.qualifications.destroy', $qualification) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.qualifications.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $qualification->id }}</td>
                    </tr>
                    <tr>
                        <th>Название</th>
                        <td>{{ $qualification->name }}</td>
                    </tr>
                    <tr>
                        <th>Минимальный стаж</th>
                        <td>{{ $qualification->min_seniority }}</td>
                    </tr>
                    <tr>
                        <th>Код</th>
                        <td>{{ $qualification->code }}</td>
                    </tr>
                    <tr>
                        <th>Описание</th>
                        <td>{{ $qualification->description ?? 'Нет описания' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
