<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\IconService;
use Illuminate\Http\Request;

class IconController extends Controller
{
    protected $iconService;

    public function __construct(IconService $iconService)
    {
        $this->iconService = $iconService;
    }

    /**
     * Display a listing of the dashboard.icons.
     */
    public function index()
    {
        $icons = $this->iconService->getAllIcons();
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

        $this->iconService->updateIcon($name, $validated['svg'], $validated['keywords']);

        return redirect()->route('dashboard.icons.index')->with('success', 'Иконка успешно обновлена.');
    }

    /**
     * Remove the specified icon from storage.
     */
    public function destroy($name)
    {
        $this->iconService->deleteIcon($name);

        return redirect()->route('dashboard.icons.index')->with('success', 'Иконка успешно удалена.');
    }
}
