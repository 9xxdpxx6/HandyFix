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
        $ordersChart = DB::table('orders')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
            
        return view('dashboard.home', compact('stats', 'latestOrders', 'ordersChart'));
    }
} 