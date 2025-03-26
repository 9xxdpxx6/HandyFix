@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Просмотр товара: {{ $product->name }}</h5>
                <div>
                    <x-permission requires="update.products">
                        <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-warning btn-sm">
                            <x-icon icon="pencil-square" class="icon-20"/> Редактировать
                        </a>
                    </x-permission>
                    
                    <x-permission requires="delete.products">
                        <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">
                                <x-icon icon="trash-can" class="icon-20"/> Удалить
                            </button>
                        </form>
                    </x-permission>
                    
                    <a href="{{ route('dashboard.products.index') }}" class="btn btn-secondary btn-sm">
                        <x-icon icon="arrow-left" class="icon-20"/> Назад
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if ($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded img-fluid mb-3">
                        @else
                            <div class="display-2">
                                <div class="bg-light rounded-2 p-1 icon-square">
                                    <i class="hf-icon hf-no-image"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h5>Информация о товаре</h5>
                        <p><strong>Артикул:</strong> {{ $product->sku ?? 'Нет артикула' }}</p>
                        <p><strong>Описание:</strong> {{ $product->description ?? 'Нет описания' }}</p>
                        <p><strong>Цена:</strong> {{ number_format($product->price, 2, ',', ' ') }} ₽</p>
                        <p><strong>Количество:</strong> {{ $product->quantity }}</p>
                        <p><strong>Категория:</strong> {{ $product->category->name }}</p>
                        <p><strong>Бренд:</strong> {{ $product->brand->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
