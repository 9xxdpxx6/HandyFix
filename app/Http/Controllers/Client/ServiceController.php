<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();
        
        // Фильтр по типу услуги
        if ($request->has('type_id') && $request->type_id) {
            $query->where('service_type_id', $request->type_id);
        }
        
        // Поиск по названию
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        $services = $query->with('serviceType')->paginate(12);
        $serviceTypes = ServiceType::all();
        
        return view('client.services.index', compact('services', 'serviceTypes'));
    }
    
    public function show(Service $service)
    {
        // Загружаем связанный тип услуги
        $service->load('serviceType');
        
        return view('client.services.show', compact('service'));
    }
} 