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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

        $filter = new ServiceFilter($request->all());
        $services = Service::filter($filter)
            ->with('serviceType')
            ->paginate($data['limit'])
            ->withQueryString();
        
        $serviceTypes = ServiceType::pluck('name', 'id');
        
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
    public function show(Service $service)
    {
        $service->load('serviceType');
        
        return view('dashboard.services.show', compact('service'));
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
