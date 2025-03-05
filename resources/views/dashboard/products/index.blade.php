@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список товаров</h1>
        <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary mb-3">Добавить продукт</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Фото</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Бренд</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded" style="width: 70px; height: 70px; object-fit: cover;">
                        @else
                            <div class="display-2">
                                <div class="bg-light rounded-2 p-1 icon-square">
                                    <i class="hf-icon hf-no-image"></i>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div>
                            {{ $product->name }}
                        </div>
                        <div class="text-secondary">
                            {{ $product->sku }}
                        </div>
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->brand->name }}</td>
                    <td>{{ $product->price }} ₽</td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        <a href="{{ route('dashboard.products.show', $product) }}" class="btn btn-info btn-sm">Просмотр</a>
                        <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
@endsection
