<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\IconService;
use App\Http\Filters\IconFilter;
use Illuminate\Http\Request;

class IconController extends Controller
{
    protected $iconService;

    public function __construct(IconService $iconService)
    {
        $this->iconService = $iconService;
        
        // Проверка прав через middleware
        $this->middleware('can:viewAny,App\Models\Icon')->only(['index']);
        $this->middleware('can:create,App\Models\Icon')->only(['create', 'store']);
        $this->middleware('can:view,App\Models\Icon')->only(['show']);
    }

    /**
     * Display a listing of the dashboard.icons.
     */
    public function index(Request $request)
    {
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:default,alphabet_asc,alphabet_desc',
        ]);

        $data['sort'] = $data['sort'] ?? 'default';
        $data['limit'] = $data['limit'] ?? 25;
        
        // Получаем все иконки
        $icons = $this->iconService->getAllIcons();
        
        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(IconFilter::class, ['queryParams' => array_filter($data)]);
        
        // Применяем фильтр к иконкам
        $icons = $filter->apply($icons);
        
        // Возвращаем представление с данными
        return view('dashboard.icons.index', compact('icons'));
    }

    /**
     * Show the form for creating a new icon.
     */
    public function create()
    {
        return view('dashboard.icons.create');
    }

    /**
     * Store a newly created icon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'svg' => 'required|string',
            'keywords' => 'required|string',
        ]);

        $this->iconService->saveIcon($validated['name'], $validated['svg'], $validated['keywords']);

        return redirect()->route('dashboard.icons.index')->with('success', 'Иконка успешно добавлена.');
    }

    /**
     * Display the specified icon.
     */
    public function show($name)
    {
        $icon = $this->iconService->getIconByName($name);
        if (!$icon) {
            return redirect()->route('dashboard.icons.index')->with('error', 'Иконка не найдена.');
        }
        
        return view('dashboard.icons.show', compact('icon'));
    }

    /**
     * Show the form for editing the specified icon.
     */
    public function edit($name)
    {
        $icon = $this->iconService->getIconByName($name);
        if (!$icon) {
            return redirect()->route('dashboard.icons.index')->with('error', 'Иконка не найдена.');
        }
        
        // Используем Gate для проверки доступа к иконкам с указанием класса и имени
        $this->authorize('update', ['App\Models\Icon', $name]);
        
        return view('dashboard.icons.edit', compact('icon'));
    }

    /**
     * Update the specified icon in storage.
     */
    public function update(Request $request, $name)
    {
        $validated = $request->validate([
            'svg' => 'required|string',
            'keywords' => 'required|string',
        ]);

        // Используем Gate для проверки доступа к иконкам с указанием класса и имени
        $this->authorize('update', ['App\Models\Icon', $name]);
        
        $this->iconService->updateIcon($name, $validated['svg'], $validated['keywords']);

        return redirect()->route('dashboard.icons.index')->with('success', 'Иконка успешно обновлена.');
    }

    /**
     * Remove the specified icon from storage.
     */
    public function destroy($name)
    {
        // Используем Gate для проверки доступа к иконкам с указанием класса и имени
        $this->authorize('delete', ['App\Models\Icon', $name]);
        
        $this->iconService->deleteIcon($name);

        return redirect()->route('dashboard.icons.index')->with('success', 'Иконка успешно удалена.');
    }
}
