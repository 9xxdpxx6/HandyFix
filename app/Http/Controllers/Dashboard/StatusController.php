<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(Status::class, 'status');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::orderBy('id', 'desc')->paginate(25);
        return view('dashboard.statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'is_closing' => 'nullable',
        ]);

        $validatedData['is_closing'] = $request->has('is_closing');

        Status::create($validatedData);

        return redirect()->route('dashboard.statuses.index')->with('success', 'Статус успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        return view('dashboard.statuses.show', compact('status'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Status $status)
    {
        return view('dashboard.statuses.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'is_closing' => 'nullable',
        ]);

        $validatedData['is_closing'] = $request->has('is_closing');

        $status->update($validatedData);

        return redirect()->route('dashboard.statuses.index')->with('success', 'Статус успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return redirect()->route('dashboard.statuses.index')->with('success', 'Статус успешно удален.');
    }
}
