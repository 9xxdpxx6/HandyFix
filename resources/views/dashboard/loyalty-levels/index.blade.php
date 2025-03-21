@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Бонусные программы</h3>
            <a href="{{ route('dashboard.loyalty-levels.create') }}" class="btn btn-primary btn-sm">Добавить новую программу</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Минимальные баллы</th>
                    <th>Скидка (%)</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($loyaltyLevels as $level)
                    <tr>
                        <td>{{ $level->id }}</td>
                        <td>{{ $level->name }}</td>
                        <td>{{ $level->min_points }}</td>
                        <td>{{ $level->discount }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.loyalty-levels.show', $level) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            <a href="{{ route('dashboard.loyalty-levels.edit', $level) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            <form action="{{ route('dashboard.loyalty-levels.destroy', $level) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                    <x-icon icon="trash-can" class="icon-20"/>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $loyaltyLevels->links() }}
            </div>
        </div>
    </div>
@endsection
