@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Информация о сотруднике</h1>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $employee->id }}</td>
            </tr>
            <tr>
                <th>ФИО</th>
                <td>{{ $employee->user?->name ?? 'Нет пользователя' }}</td>
            </tr>
            <tr>
                <th>Квалификация</th>
                <td>
                    <span>
                        {{ $employee->qualification?->name ?? 'Нет квалификации' }}
                    </span>
                    <span>
                        ({{ $employee->qualification?->code ?? '' }})
                    </span>
                </td>
            </tr>
            <tr>
                <th>Специализация</th>
                <td>
                    <span>
                        {{ $employee->specialization?->name ?? 'Нет специализации' }}
                    </span>
                    <span>
                        ({{ $employee->specialization?->code ?? '' }})
                    </span>
                </td>
            </tr>
            <tr>
                <th>Роли</th>
                <td>
                    @if ($employee->user && $employee->user->roles->isNotEmpty())
                        {{ $employee->user->roles->pluck('name')->join(', ') }}
                    @else
                        Нет ролей
                    @endif
                </td>
            </tr>
            <tr>
                <th>Фиксированная зарплата</th>
                <td>{{ $employee->fixed_salary ? number_format($employee->fixed_salary, 2, '.', ' ') : 'Не указана' }}</td>
            </tr>
            <tr>
                <th>Процент</th>
                <td>{{ $employee->commission_rate ? number_format($employee->commission_rate, 2, '.', ' ') : 'Не указан' }}</td>
            </tr>
            <tr>
                <th>Стаж (лет)</th>
                <td>{{ $employee->seniority }}</td>
            </tr>
            <tr>
                <th>Дата найма</th>
                <td>{{ \Carbon\Carbon::parse($employee->hire_date)->format('d.m.Y') }}</td>
            </tr>
            @if($employee->termination_date)
                <tr>
                    <th>Дата увольнения</th>
                    <td>{{ \Carbon\Carbon::parse($employee->termination_date)->format('d.m.Y') }}</td>
                </tr>
            @endif
        </table>
        <a href="{{ route('dashboard.employees.index') }}" class="btn btn-secondary">Назад к списку</a>
        <a href="{{ route('dashboard.employees.edit', $employee) }}" class="btn btn-warning">Редактировать</a>
    </div>
@endsection
