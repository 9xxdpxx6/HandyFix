@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Информация об услуге</h3>
            <div>
                <a href="{{ route('dashboard.services.edit', $service) }}" class="btn btn-warning btn-sm">
                    <x-icon icon="pencil-square" class="icon-20"/>
                    Редактировать
                </a>
                <a href="{{ route('dashboard.services.index') }}" class="btn btn-secondary btn-sm">
                    <x-icon icon="arrow-left" class="icon-20"/>
                    Назад
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Основная информация</h4>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">ID:</th>
                            <td>{{ $service->id }}</td>
                        </tr>
                        <tr>
                            <th>Название:</th>
                            <td>{{ $service->name }}</td>
                        </tr>
                        <tr>
                            <th>Тип услуги:</th>
                            <td>{{ $service->serviceType->name }}</td>
                        </tr>
                        <tr>
                            <th>Цена:</th>
                            <td>{{ number_format($service->price, 2, '.', ' ') }} ₽</td>
                        </tr>
                        <tr>
                            <th>Описание:</th>
                            <td>{{ $service->description ?: 'Нет описания' }}</td>
                        </tr>
                        <tr>
                            <th>Создано:</th>
                            <td>{{ $service->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Обновлено:</th>
                            <td>{{ $service->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h4>Статистика использования</h4>
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Количество записей:</th>
                            <td>{{ $service->serviceEntries()->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection 