@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Информация о бонусной программе: {{ $loyaltyLevel->name }}</h5>
                <div>
                    @can('update', $loyaltyLevel)
                    <a href="{{ route('dashboard.loyalty-levels.edit', $loyaltyLevel) }}" class="btn btn-warning btn-sm">
                        <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $loyaltyLevel)
                    <form action="{{ route('dashboard.loyalty-levels.destroy', $loyaltyLevel) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.loyalty-levels.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $loyaltyLevel->id }}</td>
                    </tr>
                    <tr>
                        <th>Название</th>
                        <td>{{ $loyaltyLevel->name }}</td>
                    </tr>
                    <tr>
                        <th>Минимальные баллы</th>
                        <td>{{ $loyaltyLevel->min_points }}</td>
                    </tr>
                    <tr>
                        <th>Скидка (%)</th>
                        <td>{{ $loyaltyLevel->discount }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
