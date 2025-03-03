<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Country;
use App\Services\IconService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $iconService;
    public function __construct(IconService $iconService)
    {
        $this->iconService = $iconService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::with('registrationCountry', 'productionCountry')->paginate(25);
        return view('dashboard.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::pluck('name', 'code');
        return view('dashboard.brands.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_original' => 'nullable',
            'registration_country_code' => 'nullable|exists:countries,code',
            'production_country_code' => 'nullable|exists:countries,code',
        ]);

        $validatedData['is_original'] = $request->has('is_original');

        Brand::create($validatedData);

        return redirect()->route('dashboard.brands.index')->with('success', 'Бренд успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return view('dashboard.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $countries = Country::pluck('name', 'code');
        return view('dashboard.brands.edit', compact('brand', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_original' => 'nullable',
            'registration_country_code' => 'nullable|exists:countries,code',
            'production_country_code' => 'nullable|exists:countries,code',
        ]);

        $validatedData['is_original'] = $request->has('is_original');

        $brand->update($validatedData);

        return redirect()->route('dashboard.brands.index')->with('success', 'Бренд успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('dashboard.brands.index')->with('success', 'Бренд успешно удален.');
    }
}
