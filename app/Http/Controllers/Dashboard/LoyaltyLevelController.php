<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyLevel;
use Illuminate\Http\Request;

class LoyaltyLevelController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(LoyaltyLevel::class, 'loyalty_level');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loyaltyLevels = LoyaltyLevel::orderBy('id', 'desc')->paginate(25);
        return view('dashboard.loyalty-levels.index', compact('loyaltyLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.loyalty-levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'min_points' => 'required|integer|min:0',
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        LoyaltyLevel::create($validatedData);

        return redirect()->route('dashboard.loyalty-levels.index')->with('success', 'Бонусная программа успешно создана.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoyaltyLevel $loyaltyLevel)
    {
        return view('dashboard.loyalty-levels.show', compact('loyaltyLevel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoyaltyLevel $loyaltyLevel)
    {
        return view('dashboard.loyalty-levels.edit', compact('loyaltyLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoyaltyLevel $loyaltyLevel)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'min_points' => 'required|integer|min:0',
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        $loyaltyLevel->update($validatedData);

        return redirect()->route('dashboard.loyalty-levels.index')->with('success', 'Бонусная программа успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoyaltyLevel $loyaltyLevel)
    {
        $loyaltyLevel->delete();

        return redirect()->route('dashboard.loyalty-levels.index')->with('success', 'Бонусная программа успешно удалена.');
    }
}
