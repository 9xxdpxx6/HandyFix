@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Добавление услуги</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.services.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Название услуги -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="name" class="form-label">Название</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Тип услуги -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="service_type_id" class="form-label">Тип услуги</label>
                        <select name="service_type_id" id="service_type_id" class="form-multi-select" data-coreui-multiple="false" data-coreui-search="true" data-coreui-options-style="text" required>
                            <option value="">Выберите тип услуги</option>
                            @foreach ($serviceTypes as $id => $name)
                                <option value="{{ $id }}" {{ old('service_type_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('service_type_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Цена -->
                    <div class="col-md-6 form-group mb-3">
                        <label for="price" class="form-label">Цена</label>
                        <div class="input-group">
                            <input type="number" name="price" id="price" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                            <span class="input-group-text">₽</span>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="col-md-12 form-group mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{ route('dashboard.services.index') }}" class="btn btn-secondary">Назад</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection 