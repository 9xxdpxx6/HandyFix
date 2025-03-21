@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Список товаров</h3>
            <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary btn-sm">Добавить товар</a>
        </div>

        <!-- Форма фильтрации -->
        <div class="card-body pb-0">
            <form method="GET" action="{{ route('dashboard.products.index') }}" class="row g-3 mb-3">
                <!-- Количество элементов на странице -->
                <div class="col-md-2">
                    <label for="limit" class="form-label visually-hidden">Отображать по</label>
                    <select name="limit" id="limit" class="form-select">
                        <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>отображать по 25</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>отображать по 50</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>отображать по 100</option>
                    </select>
                </div>

                <!-- Сортировка -->
                <div class="col-md-2">
                    <label for="sort" class="form-label visually-hidden">Сортировка</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>По умолчанию</option>
                        <option value="alphabet_asc" {{ request('sort') == 'alphabet_asc' ? 'selected' : '' }}>А-Я</option>
                        <option value="alphabet_desc" {{ request('sort') == 'alphabet_desc' ? 'selected' : '' }}>Я-А</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена (от низкой)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена (от высокой)</option>
                    </select>
                </div>

                <!-- Категория -->
                <div class="col-md-2">
                    <label for="category_id" class="form-label visually-hidden">Категория</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Все категории</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Бренд -->
                <div class="col-md-2">
                    <label for="brand_id" class="form-label visually-hidden">Бренд</label>
                    <select name="brand_id" id="brand_id" class="form-select">
                        <option value="">Все бренды</option>
                        @foreach ($brands as $id => $name)
                            <option value="{{ $id }}" {{ request('brand_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Диапазон цен -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" name="price_min" id="price_min" class="form-control" placeholder="Цена от" value="{{ request('price_min') }}">
                        <input type="number" name="price_max" id="price_max" class="form-control" placeholder="Цена до" value="{{ request('price_max') }}">
                    </div>
                </div>

                <!-- Поле поиска с кнопкой "Применить" -->
                <div class="col-md-4 ms-auto">
                    <div class="input-group">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Поиск по названию, артикулу или описанию..." value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-secondary">
                            <x-icon icon="search" class="icon-25"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Таблица товаров -->
        <div class="card-body pt-0">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Артикул</th>
                    <th>Категория</th>
                    <th>Бренд</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 50px;">
                            @else
                                <div class="display-2 d-flex justify-content-center">
                                    <div class="bg-light rounded-2 p-1 icon-square">
                                        <i class="hf-icon hf-no-image"></i>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category->name ?? 'Не указано' }}</td>
                        <td>{{ $product->brand->name ?? 'Не указано' }}</td>
                        <td>{{ number_format($product->price, 2) }} ₽</td>
                        <td>{{ $product->quantity }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                <x-icon icon="eye" class="icon-20" />
                            </a>
                            <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                <x-icon icon="pencil-square" class="icon-20" />
                            </a>
                            <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                    <x-icon icon="trash-can" class="icon-20"/>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация -->
            <div class="d-flex justify-content-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
