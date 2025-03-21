@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список статусов</h3>
            <a href="{{ route('dashboard.statuses.create') }}" class="btn btn-primary btn-sm">Добавить новый статус</a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Цвет</th>
                    <th>Закрывающий статус</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($statuses as $status)
                    <tr>
                        <td>{{ $status->id }}</td>
                        <td>{{ $status->name }}</td>
                        <td>
                            <span class="badge rounded-1 text-white" @php echo "style='background-color: {$status->color};'" @endphp>{{ $status->color }}</span>
                        </td>
                        <td>{{ $status->is_closing ? 'Да' : 'Нет' }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('dashboard.statuses.show', $status) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            <a href="{{ route('dashboard.statuses.edit', $status) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            <form action="{{ route('dashboard.statuses.destroy', $status) }}" method="POST" style="display:inline;">
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
                {{ $statuses->links() }}
            </div>
        </div>
    </div>
@endsection
