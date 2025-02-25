@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Автомобили</h1>
        <a href="{{ route('dashboard.vehicles.create') }}" class="btn btn-primary mb-3">Добавить новый автомобиль</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Марка</th>
                <th>Модель</th>
                <th>Год</th>
                <th>Гос Номер</th>
                <th>VIN</th>
                <th>Пробег</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->id }}</td>
                    <td>{{ $vehicle->customer->user->name ?? 'N/A' }}</td>
                    <td>{{ $vehicle->model->brand->name ?? 'N/A' }}</td>
                    <td>{{ $vehicle->model->name ?? 'N/A' }}</td>
                    <td>{{ $vehicle->year }}</td>
                    <td>{{ $vehicle->license_plate }}</td>
                    <td>{{ $vehicle->vin }}</td>
                    <td>{{ $vehicle->mileage }}</td>
                    <td>
                        <a href="{{ route('dashboard.vehicles.show', $vehicle) }}" class="btn btn-sm btn-info">Просмотр</a>
                        <a href="{{ route('dashboard.vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('dashboard.vehicles.destroy', $vehicle) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $vehicles->links() }}
    </div>
@endsection
