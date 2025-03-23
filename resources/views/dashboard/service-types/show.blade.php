@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Информация о типе услуги</h3>
            <div>
                <a href="{{ route('dashboard.service-types.edit', $serviceType) }}" class="btn btn-warning btn-sm me-2">
                    <x-icon icon="pencil-square" class="icon-20 me-1"/>Редактировать
                </a>
                <a href="{{ route('dashboard.service-types.index') }}" class="btn btn-secondary btn-sm">
                    <x-icon icon="arrow-left" class="icon-20 me-1"/>Назад к списку
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="mb-3">Основная информация</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">ID:</th>
                            <td>{{ $serviceType->id }}</td>
                        </tr>
                        <tr>
                            <th>Название:</th>
                            <td>
                                @if ($serviceType->icon)
                                    <x-icon icon="{{ $serviceType->icon }}" class="icon-20 me-1"/>
                                @endif
                                {{ $serviceType->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Иконка:</th>
                            <td>
                                @if ($serviceType->icon)
                                    <x-icon icon="{{ $serviceType->icon }}" class="icon-30"/> ({{ $serviceType->icon }})
                                @else
                                    <span class="text-muted">Не задана</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Описание:</th>
                            <td>{{ $serviceType->description ?: 'Нет описания' }}</td>
                        </tr>
                        <tr>
                            <th>Дата создания:</th>
                            <td>{{ $serviceType->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Дата обновления:</th>
                            <td>{{ $serviceType->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-4">
                    <h4 class="mb-3">Статистика использования</h4>
                    <div class="card">
                        <div class="card-body">
                            <h5>Количество услуг в категории</h5>
                            <h2 class="text-primary mb-0">{{ $serviceType->services_count }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($services) > 0)
                <h4 class="mt-4 mb-3">Список услуг данного типа</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>{{ $service->name }}</td>
                                <td>{{ number_format($service->price, 0, '.', ' ') }} ₽</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('dashboard.services.show', $service) }}" class="btn btn-sm btn-outline-info">
                                        <x-icon icon="eye" class="icon-20"/>
                                    </a>
                                    <a href="{{ route('dashboard.services.edit', $service) }}" class="btn btn-sm btn-outline-warning">
                                        <x-icon icon="pencil-square" class="icon-20"/>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($servicesCount > count($services))
                    <div class="text-center mt-3">
                        <a href="{{ route('dashboard.services.index', ['service_type_id' => $serviceType->id]) }}" class="btn btn-outline-primary">
                            Показать все услуги ({{ $servicesCount }})
                        </a>
                    </div>
                @endif
            @else
                <div class="alert alert-info mt-4">
                    Услуг данного типа пока нет.
                    <a href="{{ route('dashboard.services.create') }}" class="alert-link">Добавить услугу</a>
                </div>
            @endif
        </div>
    </div>
@endsection 