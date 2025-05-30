<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\ServiceTypeFilter;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(ServiceType::class, 'service_type');
    }
    
    /**
     * Display a listing of the resource.
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
        
        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(ServiceTypeFilter::class, ['queryParams' => array_filter($data)]);
        
        // Применяем фильтр к запросу
        $serviceTypes = ServiceType::filter($filter)
            ->withCount('services')
            ->paginate($data['limit']);
        
        // Возвращаем представление с данными
        return view('dashboard.service-types.index', compact('serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.service-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:service_types',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        ServiceType::create($data);
        
        return redirect()->route('dashboard.service-types.index')
            ->with('success', 'Тип услуги успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceType $serviceType)
    {
        // Получаем первые 5 услуг для отображения на странице
        $services = $serviceType->services()->get();
        
        // Получаем общее количество услуг данного типа
        $servicesCount = $serviceType->services()->count();
        
        return view('dashboard.service-types.show', compact('serviceType', 'services', 'servicesCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceType $serviceType)
    {
        return view('dashboard.service-types.edit', compact('serviceType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceType $serviceType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name,' . $serviceType->id,
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        $serviceType->update($data);
        
        return redirect()->route('dashboard.service-types.index')
            ->with('success', 'Тип услуги успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceType $serviceType)
    {
        // Проверяем, есть ли связанные услуги
        if ($serviceType->services()->exists()) {
            return redirect()->route('dashboard.service-types.index')
                ->with('error', 'Невозможно удалить тип услуги, так как с ним связаны услуги');
        }
        
        $serviceType->delete();
        
        return redirect()->route('dashboard.service-types.index')
            ->with('success', 'Тип услуги успешно удален');
    }
}
