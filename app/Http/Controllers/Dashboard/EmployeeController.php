<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Qualification;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the dashboard.employees.
     */
    public function index()
    {
        $employees = Employee::with(['user', 'qualification', 'specialization'])->paginate(10);
        return view('dashboard.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $users = User::pluck('name', 'id');
        $qualifications = Qualification::pluck('name', 'id');
        $specializations = Specialization::pluck('name', 'id');
        $roles = Role::pluck('name', 'id'); // Добавляем роли

        return view('dashboard.employees.create', compact('users', 'qualifications', 'specializations', 'roles'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'fixed_salary' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'seniority' => 'required|integer|min:0',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date',
            'role_id' => 'nullable|exists:roles,id', // Валидация роли
        ]);

        $employee = Employee::create($validated);

        // Привязка роли к пользователю
        if ($request->filled('role_id') && $employee->user) {
            $employee->user->roles()->sync([$request->role_id]);
        }

        return redirect()->route('dashboard.employees.index')->with('success', 'Сотрудник успешно добавлен.');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load(['user', 'qualification', 'specialization']);
        return view('dashboard.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $users = User::pluck('name', 'id');
        $qualifications = Qualification::pluck('name', 'id');
        $specializations = Specialization::pluck('name', 'id');
        $roles = Role::pluck('name', 'id'); // Добавляем роли

        return view('dashboard.employees.edit', compact('employee', 'users', 'qualifications', 'specializations', 'roles'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'qualification_id' => 'nullable|exists:qualifications,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'fixed_salary' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'seniority' => 'required|integer|min:0',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date',
            'role_id' => 'nullable|exists:roles,id', // Валидация роли
        ]);

        $employee->update($validated);

        // Привязка роли к пользователю
        if ($request->filled('role_id') && $employee->user) {
            $employee->user->roles()->sync([$request->role_id]);
        }

        return redirect()->route('dashboard.employees.index')->with('success', 'Сотрудник успешно обновлен.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('dashboard.employees.index')->with('success', 'Сотрудник успешно удален.');
    }
}
