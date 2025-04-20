@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список специальностей</h3>
            @can('create', \App\Models\Specialization::class)
            <a href="{{ route('dashboard.specializations.create') }}" class="btn btn-primary btn-sm">Добавить специальность</a>
            @endcan
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
                    <th>Код</th>
                    <th>Описание</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($specializations as $specialization)
                    <tr>
                        <td>{{ $specialization->id }}</td>
                        <td>{{ $specialization->name }}</td>
                        <td>{{ $specialization->code }}</td>
                        <td>{{ $specialization->description ?? 'Нет описания' }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('dashboard.specializations.show', $specialization) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20"/>
                            </a>
                            
                            @can('update', $specialization)
                            <a href="{{ route('dashboard.specializations.edit', $specialization) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20"/>
                            </a>
                            @endcan
                            
                            @can('delete', $specialization)
                            <form action="{{ route('dashboard.specializations.destroy', $specialization) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                    <x-icon icon="trash-can" class="icon-20"/>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $specializations->links() }}
            </div>
        </div>
    </div>
@endsection
