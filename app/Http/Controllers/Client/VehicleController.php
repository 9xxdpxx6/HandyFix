<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Auth::user()->customer->vehicles()->with('model.brand')->get();
        
        return view('client.vehicles.index', compact('vehicles'));
    }
    
    public function create()
    {
        $brands = Brand::with('models')->get();
        $models = VehicleModel::all();
        
        return view('client.vehicles.create', compact('brands', 'models'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20',
            'vin' => 'nullable|string|max:17',
            'mileage' => 'required|integer|min:0',
        ]);
        
        // Добавляем ID клиента
        $validated['customer_id'] = Auth::user()->customer->id;
        
        Vehicle::create($validated);
        
        return redirect()->route('vehicles.index')->with('success', 'Автомобиль успешно добавлен');
    }
    
    public function edit(Vehicle $vehicle)
    {
        // Проверяем, принадлежит ли автомобиль текущему пользователю
        if ($vehicle->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $brands = Brand::with('models')->get();
        $models = VehicleModel::all();
        
        return view('client.vehicles.edit', compact('vehicle', 'brands', 'models'));
    }
    
    public function update(Request $request, Vehicle $vehicle)
    {
        // Проверяем, принадлежит ли автомобиль текущему пользователю
        if ($vehicle->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|max:20',
            'vin' => 'nullable|string|max:17',
            'mileage' => 'required|integer|min:0',
        ]);
        
        $vehicle->update($validated);
        
        return redirect()->route('vehicles.index')->with('success', 'Автомобиль успешно обновлен');
    }
    
    public function destroy(Vehicle $vehicle)
    {
        // Проверяем, принадлежит ли автомобиль текущему пользователю
        if ($vehicle->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }
        
        // Проверяем, есть ли связанные заказы
        if ($vehicle->orders()->exists()) {
            return redirect()->route('vehicles.index')->with('error', 'Невозможно удалить автомобиль, так как с ним связаны заказы');
        }
        
        $vehicle->delete();
        
        return redirect()->route('vehicles.index')->with('success', 'Автомобиль успешно удален');
    }
} 