@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Просмотр продукта</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text"><strong>Артикул:</strong> {{ $product->sku ?? 'Нет артикула' }}</p>
                @if ($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded img-fluid mb-3" style="max-width: 200px;">
                @else
                    <div class="display-2">
                        <div class="bg-light rounded-2 p-1 icon-square">
                            <i class="hf-icon hf-no-image"></i>
                        </div>
                    </div>
                @endif
                <p class="card-text"><strong>Описание:</strong> {{ $product->description ?? 'Нет описания' }}</p>
                <p class="card-text"><strong>Цена:</strong> {{ $product->price }} ₽</p>
                <p class="card-text"><strong>Количество:</strong> {{ $product->quantity }}</p>
                <p class="card-text"><strong>Категория:</strong> {{ $product->category->name }}</p>
                <p class="card-text"><strong>Бренд:</strong> {{ $product->brand->name }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('dashboard.products.index') }}" class="btn btn-secondary">Назад к списку</a>
            <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-warning">Редактировать</a>
        </div>
    </div>
@endsection
