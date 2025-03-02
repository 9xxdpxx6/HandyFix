@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать продукт</h1>
        <form action="{{ route('dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Текущее изображение</label><br>
                @if ($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-width: 100px;">
                @else
                    <p>Изображение не загружено</p>
                @endif
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Новое изображение</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Цена</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Количество</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $product->quantity }}">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">Выберите категорию</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="brand_id" class="form-label">Бренд</label>
                <select name="brand_id" id="brand_id" class="form-select" required>
                    <option value="">Выберите бренд</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
@endsection
