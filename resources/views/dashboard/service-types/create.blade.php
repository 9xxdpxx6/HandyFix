@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Добавление типа услуги</h3>
            <a href="{{ route('dashboard.service-types.index') }}" class="btn btn-secondary btn-sm">
                <x-icon icon="arrow-left" class="icon-20 me-1"/>Назад к списку
            </a>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('dashboard.service-types.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Название</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    @include('components.icon-picker', ['selectedIcon' => null])
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3"></textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection
