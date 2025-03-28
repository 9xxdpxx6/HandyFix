@extends('layouts.client')

@section('title', 'Редактирование автомобиля')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">Мои автомобили</a></li>
                <li class="breadcrumb-item active">Редактирование автомобиля</li>
            </ol>
        </nav>
        
        <h1 class="mb-4">Редактирование автомобиля</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Марка автомобиля</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required onchange="loadModels(this.value)">
                                    <option value="">Выберите марку</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ (old('brand_id', $vehicle->model->brand_id) == $brand->id) ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="model_id" class="form-label">Модель автомобиля</label>
                                <select class="form-select @error('model_id') is-invalid @enderror" id="model_id" name="model_id" required>
                                    <option value="">Сначала выберите марку</option>
                                </select>
                                @error('model_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="year" class="form-label">Год выпуска</label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', $vehicle->year) }}" required min="1900" max="{{ date('Y') + 1 }}">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="license_plate" class="form-label">Государственный номер</label>
                                <input type="text" class="form-control @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" required maxlength="20">
                                @error('license_plate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="vin" class="form-label">VIN номер (не обязательно)</label>
                                <input type="text" class="form-control @error('vin') is-invalid @enderror" id="vin" name="vin" value="{{ old('vin', $vehicle->vin) }}" maxlength="17">
                                @error('vin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="mileage" class="form-label">Пробег (км)</label>
                                <input type="number" class="form-control @error('mileage') is-invalid @enderror" id="mileage" name="mileage" value="{{ old('mileage', $vehicle->mileage) }}" required min="0">
                                @error('mileage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Текущий автомобиль</h5>
                        <p class="card-text">
                            <strong>Марка/модель:</strong> {{ $vehicle->model->brand->name }} {{ $vehicle->model->name }}<br>
                            <strong>Год выпуска:</strong> {{ $vehicle->year }}<br>
                            <strong>Гос. номер:</strong> {{ $vehicle->license_plate }}<br>
                            <strong>VIN:</strong> {{ $vehicle->vin ?: 'Не указан' }}<br>
                            <strong>Пробег:</strong> {{ number_format($vehicle->mileage, 0, ',', ' ') }} км
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Массив с моделями для каждого бренда
    const brandModels = {
        @foreach($brands as $brand)
            {{ $brand->id }}: [
                @foreach($brand->models as $model)
                    { id: {{ $model->id }}, name: "{{ $model->name }}" },
                @endforeach
            ],
        @endforeach
    };
    
    // Функция загрузки моделей при выборе бренда
    function loadModels(brandId) {
        const modelSelect = document.getElementById('model_id');
        modelSelect.innerHTML = '<option value="">Выберите модель</option>';
        
        if (!brandId) return;
        
        const models = brandModels[brandId] || [];
        
        models.forEach(model => {
            const option = document.createElement('option');
            option.value = model.id;
            option.textContent = model.name;
            
            // Установка выбранной опции из базы данных или из старых значений формы
            @if(old('model_id'))
                if (model.id == {{ old('model_id') }}) {
                    option.selected = true;
                }
            @else
                if (model.id == {{ $vehicle->model_id }}) {
                    option.selected = true;
                }
            @endif
            
            modelSelect.appendChild(option);
        });
    }
    
    // Загрузить модели при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        const brandSelect = document.getElementById('brand_id');
        if (brandSelect.value) {
            loadModels(brandSelect.value);
        }
    });
</script>
@endpush 