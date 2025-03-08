@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список сотрудников</h1>
        <a href="{{ route('dashboard.employees.create') }}" class="btn btn-primary mb-3">Добавить сотрудника</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя пользователя</th>
                <th>Квалификация</th>
                <th>Специализация</th>
                <th>Фиксированная зарплата</th>
                <th>Стаж</th>
                <th>Дата найма</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->user?->name ?? 'Нет пользователя' }}</td>
                    <td>{{ $employee->qualification?->name ?? 'Нет квалификации' }}</td>
                    <td>{{ $employee->specialization?->name ?? 'Нет специализации' }}</td>
                    <td>{{ $employee->fixed_salary ? number_format($employee->fixed_salary, 2, '.', ' ') : 'Не указана' }}</td>
                    <td>{{ $employee->seniority }}</td>
                    <td>{{ \Carbon\Carbon::parse($employee->hire_date)->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('dashboard.employees.show', $employee) }}" class="btn btn-info btn-sm">Просмотр</a>
                        <a href="{{ route('dashboard.employees.edit', $employee) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('dashboard.employees.destroy', $employee) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $employees->links() }}
    </div>
@endsection
