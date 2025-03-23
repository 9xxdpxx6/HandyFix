@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список квалификаций</h3>
            <a href="{{ route('dashboard.qualifications.create') }}" class="btn btn-primary btn-sm">Добавить квалификацию</a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                    <th>Минимальный стаж</th>
                    <th>Код</th>
                    <th>Описание</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($qualifications as $qualification)
                    <tr>
                        <td>{{ $qualification->id }}</td>
                        <td>{{ $qualification->name }}</td>
                        <td>{{ $qualification->min_seniority }}</td>
                        <td>{{ $qualification->code }}</td>
                        <td class="text-wrap">{{ $qualification->description ?? 'Нет описания' }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('dashboard.qualifications.show', $qualification) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            <a href="{{ route('dashboard.qualifications.edit', $qualification) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            <form action="{{ route('dashboard.qualifications.destroy', $qualification) }}" method="POST" style="display:inline;">
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
                {{ $qualifications->links() }}
            </div>
        </div>
    </div>
@endsection
