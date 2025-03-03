@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список иконок</h1>
        <a href="{{ route('dashboard.icons.create') }}" class="btn btn-primary mb-3">Добавить иконку</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Icon</th>
                <th>Название</th>
                <th>Ключевые слова</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($icons as $name => $icon)
                <tr>
                    <td>
                        <x-icon icon="{{ $icon['name'] }}" class="icon-50 me-2"/>
                    </td>
                    <td>{{ $name }}</td>
                    <td>{{ $icon['keywords'] }}</td>
                    <td>
                        <a href="{{ route('dashboard.icons.show', $name) }}" class="btn btn-info btn-sm">Просмотр</a>
                        <a href="{{ route('dashboard.icons.edit', $name) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('dashboard.icons.destroy', $name) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
