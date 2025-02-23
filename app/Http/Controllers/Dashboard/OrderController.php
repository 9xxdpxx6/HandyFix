<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Status;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['customer.user', 'status'])->paginate(25);
        return view('dashboard.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $managers = Employee::whereHas('user.roles', function ($query) {
            $query->where('name', 'manager');
        })->get();
        $vehicles = Vehicle::all();
        $statuses = Status::all();

        return view('dashboard.orders.create', compact('customers', 'managers', 'statuses', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'manager_id' => 'nullable|exists:employees,id',
            'total' => 'nullable|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
            'note' => 'nullable|string',
        ]);

        Order::create($validated);

        return redirect()->route('dashboard.orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with(['customer.user', 'vehicle.brand', 'status', 'purchases', 'serviceEntries'])->findOrFail($id);
        return view('dashboard.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('dashboard.orders.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return view('dashboard.orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return view('dashboard.orders.index');
    }
}
