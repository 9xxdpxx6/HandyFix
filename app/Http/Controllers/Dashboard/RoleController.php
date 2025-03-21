<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\RoleFilter;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'permission_id' => 'nullable|array',
            'permission_id.*' => 'exists:permissions,id',
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:name_asc,name_desc,id_asc,id_desc,default',
        ]);

        // Создаем экземпляр фильтра и применяем его к запросу
        $filter = new RoleFilter($request->all());
        $roles = Role::filter($filter)->with('permissions')->paginate($request->input('limit', 25));
        
        // Получаем список разрешений для фильтра
        $permissions = Permission::all()->pluck('name', 'id');

        return view('dashboard.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[1]; // Группируем по второму слову (модулю)
        });

        return view('dashboard.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('dashboard.roles.index')->with('success', 'Роль успешно создана.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        return view('dashboard.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[1]; // Группируем по второму слову (модулю)
        });

        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($validated['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('dashboard.roles.index')->with('success', 'Роль успешно обновлена.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('dashboard.roles.index')->with('success', 'Роль успешно удалена.');
    }
}
