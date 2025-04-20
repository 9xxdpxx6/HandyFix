@extends('layouts.client')

@section('title', 'Добавление автомобиля')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">Мои автомобили</a></li>
                <li class="breadcrumb-item active">Добавление автомобиля</li>
            </ol>
        </nav>
        
        <h1 class="mb-4">Добавление нового автомобиля</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vehicles.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Марка автомобиля</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required onchange="loadModels(this.value)">
                                    <option value="">Выберите марку</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
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
                                <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year') }}" required min="1900" max="{{ date('Y') + 1 }}">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="license_plate" class="form-label">Государственный номер</label>
                                <input type="text" class="form-control @error('license_plate') is-invalid @enderror" id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required maxlength="20">
                                @error('license_plate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="vin" class="form-label">VIN номер (не обязательно)</label>
                                <input type="text" class="form-control @error('vin') is-invalid @enderror" id="vin" name="vin" value="{{ old('vin') }}" maxlength="17">
                                @error('vin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="mileage" class="form-label">Пробег (км)</label>
                                <input type="number" class="form-control @error('mileage') is-invalid @enderror" id="mileage" name="mileage" value="{{ old('mileage') }}" required min="0">
                                @error('mileage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                                <button type="submit" class="btn btn-primary">Добавить автомобиль</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Подсказка</h5>
                        <p class="card-text">Заполните все поля формы для добавления вашего автомобиля.</p>
                        <p class="card-text">VIN номер не обязателен, но его наличие поможет точнее определить характеристики вашего автомобиля.</p>
                        <p class="card-text">Указывайте актуальный пробег, это поможет нашим специалистам правильно оценить состояние вашего автомобиля.</p>
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
            
            // Установка выбранной опции, если она ранее была выбрана
            @if(old('model_id'))
                if (model.id == {{ old('model_id') }}) {
                    option.selected = true;
                }
            @endif
            
            modelSelect.appendChild(option);
        });
    }
    
    // Загрузить модели при загрузке страницы, если бренд выбран
    document.addEventListener('DOMContentLoaded', function() {
        const brandSelect = document.getElementById('brand_id');
        if (brandSelect.value) {
            loadModels(brandSelect.value);
        }
    });
</script>
@endpush 