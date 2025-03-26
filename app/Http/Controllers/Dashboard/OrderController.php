<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\ServiceEntry;
use App\Models\Status;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Конструктор с проверкой разрешений
     */
    public function __construct()
    {
        // $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Валидация параметров фильтрации
        $data = $request->validate([
            'keyword' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'manager_id' => 'nullable|exists:employees,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'status_id' => 'nullable|exists:statuses,id',
            'total_min' => 'nullable|numeric|min:0',
            'total_max' => 'nullable|numeric|min:0',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'limit' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|in:date_asc,date_desc,total_asc,total_desc,default',
        ]);

        $data['sort'] = $data['sort'] ?? 'default';
        $data['limit'] = $data['limit'] ?? 25;

        // Создаем экземпляр фильтра с валидированными данными
        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        // Применяем фильтр к запросу
        $orders = Order::filter($filter)->with(['customer.user', 'manager', 'vehicle', 'status'])->paginate($data['limit']);
        $customers = Customer::pluck('id', 'id');
        $vehicles = Vehicle::pluck('license_plate', 'id');
        $statuses = Status::pluck('name', 'id');

        // Возвращаем представление с данными
        return view('dashboard.orders.index', compact('orders', 'customers', 'vehicles', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $mechanics = Employee::whereHas('user.roles', function ($query) {
            $query->where('name', 'mechanic');
        })->get();
        $vehicles = Vehicle::all();
        $statuses = Status::all();

        return view('dashboard.orders.create', compact('customers', 'mechanics', 'statuses', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Извлекаем данные из запроса
        $data = $request->all();

        // Преобразуем JSON-строки в массивы
        if (isset($data['products'])) {
            $data['products'] = array_map(function ($product) {
                return json_decode($product, true); // Преобразуем JSON-строку в массив
            }, $data['products']);
            $data['products'] = array_merge(...$data['products']); // Разворачиваем массив
        }

        if (isset($data['services'])) {
            $data['services'] = array_map(function ($service) {
                return json_decode($service, true); // Преобразуем JSON-строку в массив
            }, $data['services']);
            $data['services'] = array_merge(...$data['services']); // Разворачиваем массив
        }

        // Валидация данных
        $validated = Validator::make($data, [
            'customer_id' => 'required|exists:customers,id',
            'status_id' => 'required|exists:statuses,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'products' => 'nullable|array', // Принимаем массив
            'products.*.id' => 'required|integer',
            'products.*.name' => 'required|string',
            'products.*.sku' => 'nullable|string',
            'products.*.price' => 'required|numeric',
            'products.*.quantity' => 'required|integer',
            'services' => 'nullable|array', // Принимаем массив
            'services.*.id' => 'required|integer',
            'services.*.name' => 'required|string',
            'services.*.code' => 'nullable|string',
            'services.*.price' => 'required|numeric',
            'services.*.quantity' => 'required|integer',
            'total' => 'required|numeric',
            'comment' => 'nullable|string',
            'note' => 'nullable|string',
        ])->validate();

        // Получаем ID менеджера (текущего авторизованного пользователя-сотрудника)
        $managerId = null;
        if (auth()->check() && auth()->user()->employee) {
            $managerId = auth()->user()->employee->id;
        }

        // Создание заказа
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'manager_id' => $managerId,
            'status_id' => $validated['status_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'total' => $validated['total'],
            'comment' => $validated['comment'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        // Обработка товаров
        if (isset($validated['products'])) {
            foreach ($validated['products'] as $productData) {
                Purchase::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['id'],
                    'price' => $productData['price'],
                    'quantity' => $productData['quantity'],
                    'product_name' => $productData['name'],
                ]);
            }
        }

        // Обработка услуг
        if (isset($validated['services'])) {
            foreach ($validated['services'] as $serviceData) {
                ServiceEntry::create([
                    'order_id' => $order->id,
                    'service_id' => $serviceData['id'],
                    'mechanic_id' => 1,
                    'price' => $serviceData['price'],
                    'quantity' => $serviceData['quantity'],
                    'service_name' => $serviceData['name'],
                ]);
            }
        }

        return redirect()->route('dashboard.orders.index')->with('success', 'Заказ успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
//        $order = $order->with(['customer.user', 'vehicle.model.brand', 'status', 'purchases', 'serviceEntries']);
        return view('dashboard.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $customers = Customer::all();
        $vehicles = Vehicle::with('model.brand')->get();
        $statuses = Status::all();
        $mechanics = Employee::whereHas('user.roles', function ($query) {
            $query->where('name', 'mechanic');
        })->get();

        $order->load(['purchases.product', 'serviceEntries.service']);

        return view('dashboard.orders.edit', compact('order', 'customers', 'mechanics', 'vehicles', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $data = $request->all();

        // Преобразуем JSON-строки в массивы для товаров и услуг
        if (isset($data['products'])) {
            $data['products'] = array_map(function ($product) {
                return json_decode($product, true);
            }, $data['products']);
            $data['products'] = array_merge(...$data['products']);
        }

        if (isset($data['services'])) {
            $data['services'] = array_map(function ($service) {
                return json_decode($service, true);
            }, $data['services']);
            $data['services'] = array_merge(...$data['services']);
        }

        // Валидация данных с учетом вложенных полей
        $validated = Validator::make($data, [
            'customer_id' => 'required|exists:customers,id',
            'status_id' => 'required|exists:statuses,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'products' => 'nullable|array', // Принимаем массив
            'products.*.id' => 'required|integer',
            'products.*.name' => 'required|string',
            'products.*.sku' => 'nullable|string',
            'products.*.price' => 'required|numeric',
            'products.*.quantity' => 'required|integer',
            'services' => 'nullable|array', // Принимаем массив
            'services.*.id' => 'required|integer',
            'services.*.name' => 'required|string',
            'services.*.code' => 'nullable|string',
            'services.*.price' => 'required|numeric',
            'services.*.quantity' => 'required|integer',
            'total' => 'required|numeric',
            'comment' => 'nullable|string',
            'note' => 'nullable|string',
        ])->validate();

        // Обновляем основные данные заказа
        $order->update([
            'customer_id' => $validated['customer_id'],
            'status_id' => $validated['status_id'],
            'vehicle_id' => $validated['vehicle_id'] ?? null,
            'total' => $validated['total'] ?? 0,
            'comment' => $validated['comment'] ?? null,
            'note' => $validated['note'] ?? null,
        ]);

        // Обработка товаров
        if (isset($validated['products'])) {
            $existingProducts = $order->purchases->pluck('product_id')->toArray();
            $newProductIds = array_column($validated['products'], 'id');
            $productsToDelete = array_diff($existingProducts, $newProductIds);

            if ($productsToDelete) {
                $order->purchases()->whereIn('product_id', $productsToDelete)->delete();
            }

            foreach ($validated['products'] as $productData) {
                $order->purchases()->updateOrCreate(
                    ['product_id' => $productData['id']],
                    [
                        'price' => $productData['price'],
                        'quantity' => $productData['quantity'],
                        'product_name' => $productData['name'] ?? null,
                    ]
                );
            }
        }

        // Обработка услуг
        if (isset($validated['services'])) {
            $existingServices = $order->serviceEntries->pluck('service_id')->toArray();
            $newServiceIds = array_column($validated['services'], 'id');
            $servicesToDelete = array_diff($existingServices, $newServiceIds);

            if ($servicesToDelete) {
                $order->serviceEntries()->whereIn('service_id', $servicesToDelete)->delete();
            }

            foreach ($validated['services'] as $serviceData) {
                $order->serviceEntries()->updateOrCreate(
                    ['service_id' => $serviceData['id']],
                    [
                        'mechanic_id' => $serviceData['mechanic_id'] ?? 1,
                        'price' => $serviceData['price'],
                        'quantity' => $serviceData['quantity'],
                        'service_name' => $serviceData['name'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('dashboard.orders.index')->with('success', 'Заказ успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('dashboard.orders.index')->with('success', 'Заказ успешно удален.');
    }
}
