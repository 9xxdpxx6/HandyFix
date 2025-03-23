<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\CategoryFilter;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:alphabet_asc,alphabet_desc,default',
        ]);

        $data['sort'] = $data['sort'] ?? 'alphabet_asc';
        $data['limit'] = $data['limit'] ?? 25;

        $filter = app()->make(CategoryFilter::class, ['queryParams' => array_filter($data)]);

        $categories = Category::whereNull('parent_id')
            ->filter($filter)
            ->with('children')
            ->paginate($data['limit']);

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::pluck('name', 'id')->toArray();
        $parents[0] = 'Нет родительской категории';
        ksort($parents);

        return view('dashboard.categories.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($data['parent_id'] == '0') {
            $data['parent_id'] = null;
        }

        $validatedData = Validator::make($data, [
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:categories,id',
        ])->validate();

        Category::create($validatedData);

        return redirect()->route('dashboard.categories.index')->with('success', 'Категория успешно создана.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parents = Category::pluck('name', 'id')->toArray();
        $parents[0] = 'Нет родительской категории';
        ksort($parents);

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->all();
        if ($data['parent_id'] == '0') {
            $data['parent_id'] = null;
        }

        $validatedData = Validator::make($data, [
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:categories,id',
        ])->validate();

        $category->update($validatedData);

        return redirect()->route('dashboard.categories.index')->with('success', 'Категория успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('dashboard.categories.index')->with('success', 'Категория успешно удалена.');
    }
}
