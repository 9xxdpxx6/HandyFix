@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Добавить сотрудника</h1>
        <form action="{{ route('dashboard.employees.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="user_id">Пользователь</label>
                    <select name="user_id" id="user_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text">
                        <option value="">Выберите пользователя</option>
                        @foreach ($users as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="qualification_id">Квалификация</label>
                    <select name="qualification_id" id="qualification_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text">
                        <option value="">Выберите квалификацию</option>
                        @foreach ($qualifications as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="specialization_id">Специализация</label>
                    <select name="specialization_id" id="specialization_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text">
                        <option value="">Выберите специализацию</option>
                        @foreach ($specializations as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="role_id">Роль</label>
                    <select name="role_id" id="role_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text">
                        <option value="">Выберите роль</option>
                        @foreach ($roles as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="fixed_salary">Фиксированная зарплата</label>
                    <input type="number" step="0.01" name="fixed_salary" id="fixed_salary" class="form-control">
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="commission_rate">Процент</label>
                    <input type="number" step="0.01" name="commission_rate" id="commission_rate" class="form-control">
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="seniority">Стаж (лет)</label>
                    <input type="number" name="seniority" id="seniority" class="form-control" required>
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="hire_date">Дата найма</label>
                    <input type="date" name="hire_date" id="hire_date" class="form-control" required>
                </div>
                <div class="col-12 col-lg-6 form-group mb-3">
                    <label for="termination_date">Дата увольнения</label>
                    <input type="date" name="termination_date" id="termination_date" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
