<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Qualification;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(Qualification::class, 'qualification');
    }
    
    /**
     * Display a listing of the dashboard.qualifications.
     */
    public function index()
    {
        $qualifications = Qualification::orderBy('id', 'desc')->paginate(25);
        return view('dashboard.qualifications.index', compact('qualifications'));
    }

    /**
     * Show the form for creating a new qualification.
     */
    public function create()
    {
        return view('dashboard.qualifications.create');
    }

    /**
     * Store a newly created qualification in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'min_seniority' => 'required|integer|min:0',
            'code' => 'required|string|max:3|unique:qualifications,code',
            'description' => 'nullable|string',
        ]);

        Qualification::create($validated);

        return redirect()->route('dashboard.qualifications.index')->with('success', 'Квалификация успешно добавлена.');
    }

    /**
     * Display the specified qualification.
     */
    public function show(Qualification $qualification)
    {
        return view('dashboard.qualifications.show', compact('qualification'));
    }

    /**
     * Show the form for editing the specified qualification.
     */
    public function edit(Qualification $qualification)
    {
        return view('dashboard.qualifications.edit', compact('qualification'));
    }

    /**
     * Update the specified qualification in storage.
     */
    public function update(Request $request, Qualification $qualification)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'min_seniority' => 'required|integer|min:0',
            'code' => 'required|string|max:3|unique:qualifications,code,' . $qualification->id,
            'description' => 'nullable|string',
        ]);

        $qualification->update($validated);

        return redirect()->route('dashboard.qualifications.index')->with('success', 'Квалификация успешно обновлена.');
    }

    /**
     * Remove the specified qualification from storage.
     */
    public function destroy(Qualification $qualification)
    {
        $qualification->delete();

        return redirect()->route('dashboard.qualifications.index')->with('success', 'Квалификация успешно удалена.');
    }
}
