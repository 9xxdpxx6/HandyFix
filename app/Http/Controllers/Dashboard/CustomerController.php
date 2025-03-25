<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\CustomerFilter;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\LoyaltyLevel;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'loyalty_level_id' => 'nullable|exists:loyalty_levels,id',
            'loyalty_points_min' => 'nullable|integer|min:0',
            'loyalty_points_max' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:name_asc,name_desc,loyalty_points_asc,loyalty_points_desc,default',
        ]);

        $data['sort'] = $data['sort'] ?? 'default';
        $data['limit'] = $data['limit'] ?? 25;

        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(CustomerFilter::class, ['queryParams' => array_filter($data)]);

        // Применяем фильтр к запросу
        $customers = Customer::filter($filter)->with('user', 'loyaltyLevel')->paginate($data['limit']);
        $loyaltyLevels = LoyaltyLevel::pluck('name', 'id');

        // Возвращаем представление с данными
        return view('dashboard.customers.index', compact('customers', 'loyaltyLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $loyaltyLevels = LoyaltyLevel::all();

        return view('dashboard.customers.create', compact('users', 'loyaltyLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'info' => 'nullable|string',
            'loyalty_points' => 'nullable|integer|min:0',
            'loyalty_level_id' => 'nullable|exists:loyalty_levels,id',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => bcrypt(Str::random(12)),
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'info' => $validatedData['info'] ?? null,
            'loyalty_points' => $validatedData['loyalty_points'] ?? 0,
            'loyalty_level_id' => $validatedData['loyalty_level_id'] ?? null,
        ]);

        return redirect()->route('dashboard.customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::with('user', 'vehicles.model.brand')->findOrFail($id);

        return view('dashboard.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $loyaltyLevels = LoyaltyLevel::all();

        return view('dashboard.customers.edit', compact('customer', 'loyaltyLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $request->user_id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $request->user_id,
            'password' => 'nullable|string|min:8',
            'info' => 'nullable|string',
            'loyalty_points' => 'nullable|integer|min:0',
            'loyalty_level_id' => 'nullable|exists:loyalty_levels,id',
        ]);

        $customer = Customer::findOrFail($id);
        $user = User::findOrFail($validatedData['user_id']);

        $user->update([
            'name' => $validatedData['name'] ?? $user->name,
            'email' => $validatedData['email'] ?? $user->email,
            'phone' => $validatedData['phone'] ?? $user->phone,
            'password' => $validatedData['password'] ?? $user->password,
        ]);

        $customer->update([
            'user_id' => $user->id,
            'info' => $validatedData['info'] ?? $customer->info,
            'loyalty_points' => $validatedData['loyalty_points'] ?? $customer->loyalty_points,
            'loyalty_level_id' => $validatedData['loyalty_level_id'] ?? $customer->loyalty_level_id,
        ]);

        return redirect()->route('dashboard.customers.index')->with('success', 'Customer updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return redirect()->route('dashboard.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
