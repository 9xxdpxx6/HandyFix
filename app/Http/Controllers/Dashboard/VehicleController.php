<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::with('customer', 'model')->paginate(25);
        return view('dashboard.vehicles.index', compact('vehicles'));
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
    public function show(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('dashboard.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $brands = Brand::where('is_original', '1')->get();
        $models = VehicleModel::all();
        $customers = Customer::all();

        return view('dashboard.vehicles.edit', compact('vehicle', 'brands', 'models', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

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
    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('dashboard.vehicles.index')->with('success', 'Vehicle deleted successfully.');
    }
}
