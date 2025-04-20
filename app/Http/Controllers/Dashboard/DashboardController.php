<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Конструктор с проверкой прав доступа
     */
    public function __construct()
    {
        // Любой авторизованный пользователь имеет доступ к дашборду
        $this->middleware('auth');
    }

    /**
     * Отображает главную страницу панели управления
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Получаем статистические данные для дашборда
        $stats = [
            'orders_count' => Order::count(),
            'customers_count' => Customer::count(),
            'vehicles_count' => Vehicle::count(),
            'employees_count' => Employee::count(),
            'products_count' => Product::count(),
            'orders_total' => Order::sum('total'),
        ];

        // Последние 5 заказов
        $latestOrders = Order::with(['customer', 'status'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // График заказов по месяцам (последние 6 месяцев)
        $orders = Order::where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m');
            })
            ->map(function ($group) {
                return $group->count();
            });

        // Преобразуем результат в массив
        $ordersChart = $orders->toArray();

        return view('dashboard.home', compact('stats', 'latestOrders', 'ordersChart'));
    }
}
