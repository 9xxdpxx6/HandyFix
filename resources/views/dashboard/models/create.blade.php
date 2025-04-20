@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Создание новой модели автомобиля</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.models.store') }}" method="POST">
                    @csrf

                    <!-- Бренд -->
                    <div class="mb-3">
                        <label for="brand_id" class="form-label">Бренд</label>
                        <select name="brand_id" id="brand_id" class="form-select" required>
                            <option value="">Выберите бренд</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Название модели -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Название модели</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <!-- Поколение -->
                    <div class="mb-3">
                        <label for="generation" class="form-label">Поколение</label>
                        <input type="text" name="generation" id="generation" class="form-control" value="{{ old('generation') }}" placeholder="Например: Mk1, I, 1-е поколение">
                    </div>

                    <!-- Год начала производства -->
                    <div class="mb-3">
                        <label for="start_year" class="form-label">Год начала производства</label>
                        <input type="number" name="start_year" id="start_year" class="form-control" 
                            value="{{ old('start_year') }}" min="1900" max="{{ date('Y') + 1 }}" required>
                    </div>

                    <!-- Год окончания производства -->
                    <div class="mb-3">
                        <label for="end_year" class="form-label">Год окончания производства</label>
                        <input type="number" name="end_year" id="end_year" class="form-control" 
                            value="{{ old('end_year') }}" min="1900" max="{{ date('Y') + 10 }}"
                            placeholder="Оставьте пустым, если производство продолжается">
                    </div>

                    <!-- Рестайлинг -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_facelift" id="is_facelift" value="1" {{ old('is_facelift') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_facelift">
                                Модель является рестайлингом
                            </label>
                        </div>
                    </div>

                    <!-- Год рестайлинга -->
                    <div class="mb-3" id="facelift_year_container" style="{{ old('is_facelift') ? '' : 'display: none;' }}">
                        <label for="facelift_year" class="form-label">Год рестайлинга</label>
                        <input type="number" name="facelift_year" id="facelift_year" class="form-control" 
                            value="{{ old('facelift_year') }}" min="1900" max="{{ date('Y') + 1 }}">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{ route('dashboard.models.index') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFaceliftCheckbox = document.getElementById('is_facelift');
        const faceliftYearContainer = document.getElementById('facelift_year_container');
        
        isFaceliftCheckbox.addEventListener('change', function() {
            faceliftYearContainer.style.display = this.checked ? 'block' : 'none';
        });
    });
</script>
@endpush 