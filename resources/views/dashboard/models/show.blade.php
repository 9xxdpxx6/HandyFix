@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">Информация о модели</h3>
                <div>
                    @can('update', $model)
                    <a href="{{ route('dashboard.models.edit', $model) }}" class="btn btn-warning">
                        <x-icon icon="pencil-square" class="icon-20" /> Редактировать
                    </a>
                    @endcan
                    
                    @can('delete', $model)
                    <form action="{{ route('dashboard.models.destroy', $model) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены? Это может повлиять на связанные автомобили.')">
                            <x-icon icon="trash-can" class="icon-20"/> Удалить
                        </button>
                    </form>
                    @endcan
                    
                    <a href="{{ route('dashboard.models.index') }}" class="btn btn-secondary">
                        <x-icon icon="arrow-left" class="icon-20" /> Назад
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Основная информация</h4>
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 30%">ID:</th>
                                <td>{{ $model->id }}</td>
                            </tr>
                            <tr>
                                <th>Название:</th>
                                <td>{{ $model->name }}</td>
                            </tr>
                            <tr>
                                <th>Бренд:</th>
                                <td>
                                    <a href="{{ route('dashboard.brands.show', $model->brand) }}">
                                        {{ $model->brand->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Поколение:</th>
                                <td>{{ $model->generation ?? 'Не указано' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Дополнительная информация</h4>
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 30%">Год начала производства:</th>
                                <td>{{ $model->start_year }}</td>
                            </tr>
                            <tr>
                                <th>Год окончания производства:</th>
                                <td>{{ $model->end_year ?? 'По настоящее время' }}</td>
                            </tr>
                            <tr>
                                <th>Рестайлинг:</th>
                                <td>{{ $model->is_facelift ? 'Да' : 'Нет' }}</td>
                            </tr>
                            @if($model->is_facelift)
                            <tr>
                                <th>Год рестайлинга:</th>
                                <td>{{ $model->facelift_year ?? 'Не указан' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                <!-- Связанные автомобили -->
                <div class="mt-4">
                    <h4>Количество связанных автомобилей: {{ $vehiclesCount }}</h4>
                    @if($vehiclesCount > 0)
                        <a href="{{ route('dashboard.vehicles.index', ['model_id' => $model->id]) }}" class="btn btn-primary">Просмотреть автомобили</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 