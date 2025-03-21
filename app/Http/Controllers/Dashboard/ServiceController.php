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
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'service_type_id' => 'nullable|exists:service_types,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:alphabet_asc,alphabet_desc,price_asc,price_desc,default',
        ]);

        $data['sort'] = $data['sort'] ?? 'default';
        $data['limit'] = $data['limit'] ?? 25;

        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(ServiceFilter::class, ['queryParams' => array_filter($data)]);

        // Применяем фильтр к запросу
        $services = Service::filter($filter)->with('serviceType')->paginate($data['limit']);
        $serviceTypes = ServiceType::pluck('name', 'id');

        // Возвращаем представление с данными
        return view('dashboard.services.index', compact('services', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
