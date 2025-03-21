@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список сотрудников</h3>
            <a href="{{ route('dashboard.employees.create') }}" class="btn btn-primary btn-sm">Добавить сотрудника</a>
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.employees.index') }}" class="row g-3 mb-3">
                <!-- Количество элементов на странице -->
                <div class="col-md-2">
                    <label for="limit" class="form-label visually-hidden">Отображать по</label>
                    <select name="limit" id="limit" class="form-select">
                        <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>Отображать по 25</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>Отображать по 50</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>Отображать по 100</option>
                    </select>
                </div>

                <!-- Сортировка -->
                <div class="col-md-2">
                    <label for="sort" class="form-label visually-hidden">Сортировка</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>По умолчанию</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Имя (А-Я)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Имя (Я-А)</option>
                        <option value="seniority_asc" {{ request('sort') == 'seniority_asc' ? 'selected' : '' }}>Стаж (по возрастанию)</option>
                        <option value="seniority_desc" {{ request('sort') == 'seniority_desc' ? 'selected' : '' }}>Стаж (по убыванию)</option>
                        <option value="hire_date_asc" {{ request('sort') == 'hire_date_asc' ? 'selected' : '' }}>Дата найма (старые)</option>
                        <option value="hire_date_desc" {{ request('sort') == 'hire_date_desc' ? 'selected' : '' }}>Дата найма (новые)</option>
                    </select>
                </div>

                <!-- Квалификация -->
                <div class="col-md-2">
                    <label for="qualification_id" class="form-label visually-hidden">Квалификация</label>
                    <select name="qualification_id" id="qualification_id" class="form-select">
                        <option value="">Все квалификации</option>
                        @foreach ($qualifications as $id => $name)
                            <option value="{{ $id }}" {{ request('qualification_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Специализация -->
                <div class="col-md-2">
                    <label for="specialization_id" class="form-label visually-hidden">Специализация</label>
                    <select name="specialization_id" id="specialization_id" class="form-select">
                        <option value="">Все специализации</option>
                        @foreach ($specializations as $id => $name)
                            <option value="{{ $id }}" {{ request('specialization_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Диапазон стажа -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" name="seniority_min" id="seniority_min" class="form-control" placeholder="Стаж от" value="{{ request('seniority_min') }}" min="0">
                        <input type="number" name="seniority_max" id="seniority_max" class="form-control" placeholder="Стаж до" value="{{ request('seniority_max') }}" min="0">
                    </div>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-4 ms-auto">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск по имени..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица сотрудников -->
        <div class="card-body pt-0">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Имя пользователя</th>
                    <th>Квалификация</th>
                    <th>Специализация</th>
                    <th>Фиксированная зарплата</th>
                    <th>Стаж</th>
                    <th>Дата найма</th>
                    <th class="text-end">Действия</th>
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
                        <td class="text-end">
                            <a href="{{ route('dashboard.employees.show', $employee) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20" />
                            </a>
                            <a href="{{ route('dashboard.employees.edit', $employee) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20" />
                            </a>
                            <form action="{{ route('dashboard.employees.destroy', $employee) }}" method="POST" style="display:inline;">
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
                {{ $employees->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
