<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\ServiceFilter;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(Service::class, 'service');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'service_type_id' => 'nullable|exists:service_types,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'limit' => 'nullable|numeric|min:1',
            'sort' => 'nullable|string|in:default,alphabet_asc,alphabet_desc,price_asc,price_desc',
        ]);

        $data['sort'] = $data['sort'] ?? 'default';
        $data['limit'] = $data['limit'] ?? 25;

        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(ServiceFilter::class, ['queryParams' => array_filter($data)]);

        // Применяем фильтр к запросу
        $services = Service::filter($filter)
            ->with('serviceType')
            ->paginate($data['limit']);
        
        $serviceTypes = ServiceType::pluck('name', 'id');
        
        // Возвращаем представление с данными
        return view('dashboard.services.index', compact('services', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceTypes = ServiceType::pluck('name', 'id');
        
        return view('dashboard.services.create', compact('serviceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service_type_id' => 'required|exists:service_types,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        
        Service::create($data);
        
        return redirect()->route('dashboard.services.index')
            ->with('success', 'Услуга успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service, Request $request)
    {
        $showAllPrices = $request->has('show_all_prices');
        
        $service->load(['serviceType']);
        
        // Загружаем историю цен
        $pricesQuery = $service->servicePrices()->orderBy('created_at', 'desc');
        
        if (!$showAllPrices) {
            $pricesQuery->limit(15);
        }
        
        $service->setPriceHistory($pricesQuery->get()->sortBy('created_at'));
        
        return view('dashboard.services.show', compact('service', 'showAllPrices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $serviceTypes = ServiceType::pluck('name', 'id');
        
        return view('dashboard.services.edit', compact('service', 'serviceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service_type_id' => 'required|exists:service_types,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        
        $service->update($data);
        
        return redirect()->route('dashboard.services.index')
            ->with('success', 'Услуга успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        
        return redirect()->route('dashboard.services.index')
            ->with('success', 'Услуга успешно удалена');
    }
}
