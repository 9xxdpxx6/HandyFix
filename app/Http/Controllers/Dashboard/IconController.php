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
        
        // Проверка прав
        $this->middleware('can:viewAny,Icon')->only(['index']);
        $this->middleware('can:view,icon')->only(['show']);
        $this->middleware('can:create,Icon')->only(['create', 'store']);
        $this->middleware('can:update,icon')->only(['edit', 'update']);
        $this->middleware('can:delete,icon')->only(['destroy']);
    }

    /**
     * Display a listing of the dashboard.icons.
     */
    public function index(Request $request)
    {
        $icons = $this->iconService->getAllIcons();
        
        $filter = new IconFilter($request->all());
        $icons = $filter->apply($icons);
        
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
        
        // Используем Gate чтобы проверить доступ к конкретной иконке
        $this->authorize('view', $name);
        
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
        
        // Используем Gate чтобы проверить доступ к конкретной иконке
        $this->authorize('update', $name);
        
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

        // Используем Gate чтобы проверить доступ к конкретной иконке
        $this->authorize('update', $name);
        
        $this->iconService->updateIcon($name, $validated['svg'], $validated['keywords']);

        return redirect()->route('dashboard.icons.index')->with('success', 'Иконка успешно обновлена.');
    }

    /**
     * Remove the specified icon from storage.
     */
    public function destroy($name)
    {
        // Используем Gate чтобы проверить доступ к конкретной иконке
        $this->authorize('delete', $name);
        
        $this->iconService->deleteIcon($name);

        return redirect()->route('dashboard.icons.index')->with('success', 'Иконка успешно удалена.');
    }
}
