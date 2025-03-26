<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\VehicleModelFilter;
use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(VehicleModel::class, 'model');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Получаем все параметры фильтрации из запроса
        $data = $request->all();
        
        // Получаем значение лимита из запроса или устанавливаем по умолчанию 25
        $limit = $request->input('limit', 25);
        
        // Создаем экземпляр фильтра
        $filter = app()->make(VehicleModelFilter::class, ['queryParams' => array_filter($data)]);
        
        // Получаем модели с применением фильтра и пагинацией
        $models = VehicleModel::with('brand')
            ->filter($filter)
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();
            
        // Получаем все бренды для фильтра
        $brands = Brand::all();
        
        // Возвращаем представление с данными
        return view('dashboard.models.index', compact('models', 'brands', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Получаем все бренды для выпадающего списка
        $brands = Brand::all();
        
        return view('dashboard.models.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'generation' => 'nullable|string|max:50',
            'start_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'is_facelift' => 'boolean',
            'facelift_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        ]);
        
        // Создаем новую модель
        VehicleModel::create($validated);
        
        // Редирект на список с сообщением об успехе
        return redirect()->route('dashboard.models.index')
            ->with('success', 'Модель успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleModel $model)
    {
        // Загружаем связанный бренд
        $model->load('brand');
        
        // Получаем количество автомобилей этой модели
        $vehiclesCount = $model->vehicles()->count();
        
        return view('dashboard.models.show', compact('model', 'vehiclesCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleModel $model)
    {
        // Получаем все бренды для выпадающего списка
        $brands = Brand::all();
        
        return view('dashboard.models.edit', compact('model', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleModel $model)
    {
        // Валидация данных
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'generation' => 'nullable|string|max:50',
            'start_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'is_facelift' => 'boolean',
            'facelift_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        ]);
        
        // Обновляем модель
        $model->update($validated);
        
        // Редирект на список с сообщением об успехе
        return redirect()->route('dashboard.models.index')
            ->with('success', 'Модель успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleModel $model)
    {
        // Проверяем, есть ли связанные автомобили
        $vehiclesCount = $model->vehicles()->count();
        
        if ($vehiclesCount > 0) {
            return redirect()->route('dashboard.models.index')
                ->with('error', "Невозможно удалить модель. С ней связано {$vehiclesCount} автомобилей.");
        }
        
        // Удаляем модель
        $model->delete();
        
        // Редирект на список с сообщением об успехе
        return redirect()->route('dashboard.models.index')
            ->with('success', 'Модель успешно удалена');
    }
} 