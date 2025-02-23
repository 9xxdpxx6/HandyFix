<?php

namespace App\Http\Controllers;

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
     * Получить все иконки.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $icons = $this->iconService->getAllIcons();
        return response()->json($icons);
    }

    /**
     * Получить иконку по имени.
     *
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $name)
    {
        $icon = $this->iconService->getIconByName($name);

        if (!$icon) {
            return response()->json(['error' => 'Icon not found'], 404);
        }

        return response()->json($icon);
    }

    /**
     * Добавить или обновить иконку.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'svg' => 'required|string',
            'keywords' => 'nullable|array',
        ]);

        $this->iconService->saveIcon(
            $request->input('name'),
            $request->input('svg'),
            $request->input('keywords', [])
        );

        return response()->json(['message' => 'Icon saved successfully']);
    }

    /**
     * Обновить существующую иконку.
     *
     * @param string $name
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $name, Request $request)
    {
        $request->validate([
            'svg' => 'required|string',
            'keywords' => 'nullable|array',
        ]);

        $this->iconService->updateIcon(
            $name,
            $request->input('svg'),
            $request->input('keywords', [])
        );

        return response()->json(['message' => 'Icon updated successfully']);
    }

    /**
     * Удалить иконку.
     *
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $name)
    {
        $this->iconService->deleteIcon($name);
        return response()->json(['message' => 'Icon deleted successfully']);
    }
}
