@extends('layouts.app')

@section('title', 'Список клиентов')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Клиенты</h3>
            <div class="card-tools">
                <a href="{{ route('dashboard.customers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Добавить клиента
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Бонусные баллы</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->user->name ?? 'N/A' }}</td>
                        <td>{{ $customer->user->email ?? 'N/A' }}</td>
                        <td>{{ $customer->user->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->loyalty_points }}</td>
                        <td>
                            <a href="{{ route('dashboard.customers.show', $customer->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Просмотр
                            </a>
                            <a href="{{ route('dashboard.customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Редактировать
                            </a>
                            <form action="{{ route('dashboard.customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                    <i class="fas fa-trash"></i> Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
