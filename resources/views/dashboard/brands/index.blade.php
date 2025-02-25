@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список брендов</h1>
        <a href="{{ route('dashboard.brands.create') }}" class="btn btn-primary mb-3">Добавить новый бренд</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Иконка</th>
                <th>Описание</th>
                <th>Оригинальный</th>
                <th>Страна регистрации</th>
                <th>Страна производства</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($brands as $brand)
                <tr>
                    <td>{{ $brand->id }}</td>
                    <td>{{ $brand->name }}</td>
                    <td><i class="display-1 hf-icon {{ $brand->icon }}"></i></td>
                    <td>{{ $brand->description }}</td>
                    <td>{{ $brand->is_original ? 'Да' : 'Нет' }}</td>
                    <td>{{ $brand->registrationCountry?->name ?? 'Не указано' }}</td>
                    <td>{{ $brand->productionCountry?->name ?? 'Не указано' }}</td>
                    <td>
                        <a href="{{ route('dashboard.brands.show', $brand) }}" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="{{ route('dashboard.brands.edit', $brand) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('dashboard.brands.destroy', $brand) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $brands->links() }}
    </div>
@endsection
