<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(Specialization::class, 'specialization');
    }
    
    /**
     * Display a listing of the dashboard.specializations.
     */
    public function index()
    {
        $specializations = Specialization::paginate(25);
        return view('dashboard.specializations.index', compact('specializations'));
    }

    /**
     * Show the form for creating a new specialization.
     */
    public function create()
    {
        return view('dashboard.specializations.create');
    }

    /**
     * Store a newly created specialization in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:4|unique:specializations,code',
            'description' => 'nullable|string',
        ]);

        Specialization::create($validated);

        return redirect()->route('dashboard.specializations.index')->with('success', 'Специализация успешно добавлена.');
    }

    /**
     * Display the specified specialization.
     */
    public function show(Specialization $specialization)
    {
        return view('dashboard.specializations.show', compact('specialization'));
    }

    /**
     * Show the form for editing the specified specialization.
     */
    public function edit(Specialization $specialization)
    {
        return view('dashboard.specializations.edit', compact('specialization'));
    }

    /**
     * Update the specified specialization in storage.
     */
    public function update(Request $request, Specialization $specialization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:4|unique:specializations,code,' . $specialization->id,
            'description' => 'nullable|string',
        ]);

        $specialization->update($validated);

        return redirect()->route('dashboard.specializations.index')->with('success', 'Специализация успешно обновлена.');
    }

    /**
     * Remove the specified specialization from storage.
     */
    public function destroy(Specialization $specialization)
    {
        $specialization->delete();

        return redirect()->route('dashboard.specializations.index')->with('success', 'Специализация успешно удалена.');
    }
}
