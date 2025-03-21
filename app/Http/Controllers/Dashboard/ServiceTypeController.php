<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\ServiceTypeFilter;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'sort' => 'nullable|string|in:name_asc,name_desc,id_asc,id_desc',
        ]);
        
        $filter = new ServiceTypeFilter($request->all());
        $serviceTypes = ServiceType::filter($filter)
            ->withCount('services')
            ->paginate(25)
            ->withQueryString();
        
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
        $serviceType->load(['services' => function ($query) {
            $query->take(5);
        }]);
        
        $servicesCount = $serviceType->services()->count();
        
        return view('dashboard.service-types.show', compact('serviceType', 'servicesCount'));
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
