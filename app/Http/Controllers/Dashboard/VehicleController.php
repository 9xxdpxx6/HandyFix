<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\VehicleFilter;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(Vehicle::class, 'vehicle');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'model_id' => 'nullable|exists:vehicle_models,id',
            'brand_id' => 'nullable|exists:brands,id',
            'year_min' => 'nullable|integer|min:1900',
            'year_max' => 'nullable|integer|max:' . date('Y'),
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:year_asc,year_desc,license_plate_asc,license_plate_desc,default',
        ]);

        $data['sort'] = $data['sort'] ?? 'default';
        $data['limit'] = $data['limit'] ?? 25;

        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(VehicleFilter::class, ['queryParams' => array_filter($data)]);

        // Применяем фильтр к запросу
        $vehicles = Vehicle::filter($filter)->with('customer', 'model.brand')->paginate($data['limit']);
        $customers = Customer::all();
        $brands = Brand::where('is_original', '1')->get();
        $models = VehicleModel::all();

        // Возвращаем представление с данными
        return view('dashboard.vehicles.index', compact('vehicles', 'customers', 'brands', 'models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::where('is_original', '1')->get();
        $models = VehicleModel::all();
        $customers = Customer::all();

        return view('dashboard.vehicles.create', compact('brands', 'models', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'license_plate' => 'required|string|max:255',
            'vin' => 'nullable|string|max:255|unique:vehicles,vin',
            'mileage' => 'nullable|numeric|min:0',
        ]);

        Vehicle::create($validatedData);

        return redirect()->route('dashboard.vehicles.index')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return view('dashboard.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $brands = Brand::where('is_original', '1')->get();
        $models = VehicleModel::all();
        $customers = Customer::all();

        return view('dashboard.vehicles.edit', compact('vehicle', 'brands', 'models', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'license_plate' => 'required|string|max:255',
            'vin' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('vehicles')->ignore($vehicle->id),
            ],
            'mileage' => 'nullable|numeric|min:0',
        ]);

        $vehicle->update($validatedData);

        return redirect()->route('dashboard.vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('dashboard.vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }
}
