<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the dashboard.products.
     */
    public function index(Request $request)
    {
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:alphabet_asc,alphabet_desc,price_asc,price_desc,default',
        ]);

        $data['sort'] = $data['sort'] ?? 'default';
        $data['limit'] = $data['limit'] ?? 25;

        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($data)]);

        // Применяем фильтр к запросу
        $products = Product::filter($filter)->with(['category', 'brand'])->paginate($data['limit']);
        $categories = Category::pluck('name', 'id');
        $brands = Brand::pluck('name', 'id');

        // Возвращаем представление с данными
        return view('dashboard.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('dashboard.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимум 2 МБ
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Продукт успешно создан.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('dashboard.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('dashboard.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимум 2 МБ
        ]);

        // Удаление старого изображения
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Продукт успешно обновлен.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Удаление изображения
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Продукт успешно удален.');
    }
}
