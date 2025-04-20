<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ServiceEntry;
use App\Models\Status;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Статистика заказов
     */
    public function orders(Request $request)
    {
        $this->authorize('viewOrders', 'Statistics');
        
        // Период по умолчанию - последние 30 дней
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Заказы по статусам
        $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
            ->select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->get()
            ->map(function($item) {
                $status = Status::find($item->status_id);
                $item->status_name = $status ? $status->name : 'Нет статуса';
                $item->status_color = $status ? $status->color : '#ccc';
                return $item;
            });

        // Количество заказов по дням (только выполненные)
        $ordersByDate = Order::whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->select(DB::raw('DATE(orders.created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Общее количество заказов (только выполненные)
        $totalOrders = Order::whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->count();
        
        // Средняя сумма заказа (только выполненные)
        $averageOrderTotal = Order::whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->avg('orders.total') ?? 0;
            
        // Топ 5 услуг по популярности (только выполненные заказы)
        $topServices = ServiceEntry::whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                      ->whereHas('status', function($q) {
                          $q->where('is_closing', true);
                      });
            })
            ->select('service_name', DB::raw('sum(quantity) as total'))
            ->groupBy('service_name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Топ 5 продуктов по популярности (только выполненные заказы)
        $topProducts = Purchase::whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                      ->whereHas('status', function($q) {
                          $q->where('is_closing', true);
                      });
            })
            ->select('product_name', DB::raw('sum(quantity) as total'))
            ->groupBy('product_name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.statistics.orders', compact(
            'startDate', 
            'endDate', 
            'ordersByStatus', 
            'ordersByDate', 
            'totalOrders', 
            'averageOrderTotal',
            'topServices',
            'topProducts'
        ));
    }

    /**
     * Статистика автомобилей
     */
    public function vehicles(Request $request)
    {
        $this->authorize('viewVehicles', 'Statistics');
        
        // Количество автомобилей по брендам
        $vehiclesByBrand = Vehicle::select('model_id', DB::raw('count(*) as total'))
            ->groupBy('model_id')
            ->with('model.brand')
            ->get()
            ->groupBy(function($item) {
                return $item->model->brand->name ?? 'Нет бренда';
            })
            ->map(function($items) {
                return $items->sum('total');
            })
            ->sortDesc();

        // Статистика по годам выпуска
        $vehiclesByYear = Vehicle::select('year', DB::raw('count(*) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Общее количество автомобилей
        $totalVehicles = Vehicle::count();

        // Среднее количество заказов на один автомобиль (только выполненные)
        $ordersPerVehicle = Vehicle::withCount(['orders' => function($query) {
                $query->join('statuses', 'orders.status_id', '=', 'statuses.id')
                      ->where('statuses.is_closing', true);
            }])
            ->get()
            ->avg('orders_count') ?? 0;
            
        // Топовые услуги для каждого бренда автомобилей (только выполненные заказы)
        $topServicesByBrand = [];
        
        foreach ($vehiclesByBrand as $brand => $count) {
            // Получаем топ-3 популярные услуги для каждого бренда
            $services = ServiceEntry::select('service_name', DB::raw('count(*) as total'))
                ->join('orders', 'service_entries.order_id', '=', 'orders.id')
                ->join('statuses', 'orders.status_id', '=', 'statuses.id')
                ->join('vehicles', 'orders.vehicle_id', '=', 'vehicles.id')
                ->join('vehicle_models', 'vehicles.model_id', '=', 'vehicle_models.id')
                ->join('brands', 'vehicle_models.brand_id', '=', 'brands.id')
                ->where('brands.name', $brand)
                ->where('statuses.is_closing', true)
                ->groupBy('service_name')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
            
            $topServicesByBrand[$brand] = $services;
        }

        return view('dashboard.statistics.vehicles', compact(
            'vehiclesByBrand',
            'vehiclesByYear',
            'totalVehicles',
            'ordersPerVehicle',
            'topServicesByBrand'
        ));
    }

    /**
     * Статистика клиентов
     */
    public function customers(Request $request)
    {
        $this->authorize('viewCustomers', 'Statistics');
        
        // Период по умолчанию - последние 30 дней
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Новые клиенты по дням
        $newCustomersByDate = Customer::whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Клиенты по уровням лояльности
        $customersByLoyalty = Customer::select('loyalty_level_id', DB::raw('count(*) as total'))
            ->groupBy('loyalty_level_id')
            ->with('loyaltyLevel')
            ->get()
            ->map(function($item) {
                $item->level_name = $item->loyaltyLevel ? $item->loyaltyLevel->name : 'Без уровня';
                return $item;
            });

        // Топ 10 клиентов по количеству заказов
        $topCustomersByOrders = Customer::withCount(['orders' => function($query) {
                $query->join('statuses', 'orders.status_id', '=', 'statuses.id')
                      ->where('statuses.is_closing', true);
            }])
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->with('user')
            ->get();

        // Топ 10 клиентов по сумме заказов
        $topCustomersByTotal = Customer::with('user')
            ->select('customers.id', 'customers.user_id', DB::raw('SUM(orders.total) as total_spent'))
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->groupBy('customers.id', 'customers.user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Общее количество клиентов
        $totalCustomers = Customer::count();

        // Новые клиенты за выбранный период
        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])->count();

        return view('dashboard.statistics.customers', compact(
            'startDate',
            'endDate',
            'newCustomersByDate',
            'customersByLoyalty',
            'topCustomersByOrders',
            'topCustomersByTotal',
            'totalCustomers',
            'newCustomers'
        ));
    }

    /**
     * Статистика сотрудников
     */
    public function employees(Request $request)
    {
        $this->authorize('viewEmployees', 'Statistics');
        
        // Период по умолчанию - последние 30 дней
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Топ 10 сотрудников по выполненным услугам
        $topEmployeesByServices = Employee::with(['user', 'specialization', 'qualification'])
            ->select('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id', DB::raw('COUNT(service_entries.id) as services_count'))
            ->leftJoin('service_entries', 'employees.id', '=', 'service_entries.mechanic_id')
            ->leftJoin('orders', 'service_entries.order_id', '=', 'orders.id')
            ->leftJoin('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->groupBy('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id')
            ->orderBy('services_count', 'desc')
            ->limit(10)
            ->get();

        // Топ 10 менеджеров по количеству заказов
        $topManagersByOrders = Employee::with(['user', 'specialization', 'qualification'])
            ->select('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id', DB::raw('COUNT(orders.id) as orders_count'))
            ->leftJoin('orders', 'employees.id', '=', 'orders.manager_id')
            ->leftJoin('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->groupBy('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id')
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->get();

        // Сотрудники по стажу работы
        $employeesBySeniority = Employee::select(DB::raw('FLOOR(seniority) as seniority_years'), DB::raw('count(*) as total'))
            ->groupBy('seniority_years')
            ->orderBy('seniority_years')
            ->get();

        // Количество сотрудников по специализациям
        $employeesBySpecialization = Employee::select('specialization_id', DB::raw('count(*) as total'))
            ->groupBy('specialization_id')
            ->with('specialization')
            ->get()
            ->map(function($item) {
                $item->specialization_name = $item->specialization ? $item->specialization->name : 'Без специализации';
                return $item;
            });

        return view('dashboard.statistics.employees', compact(
            'startDate',
            'endDate',
            'topEmployeesByServices',
            'topManagersByOrders',
            'employeesBySeniority',
            'employeesBySpecialization'
        ));
    }

    /**
     * Статистика товаров
     */
    public function products(Request $request)
    {
        $this->authorize('viewProducts', 'Statistics');
        
        // Период по умолчанию - последние 30 дней
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Топ 10 самых продаваемых товаров
        $topSellingProducts = Purchase::with('product')
            ->select('product_id', DB::raw('sum(quantity) as total_quantity'))
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                      ->whereHas('status', function($q) {
                          $q->where('is_closing', true);
                      });
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Товары с низким запасом с учетом частоты использования
        $lowStockProducts = Product::select('products.*', DB::raw('IFNULL(SUM(purchases.quantity), 0) as usage_frequency'))
            ->leftJoin('purchases', 'products.id', '=', 'purchases.product_id')
            ->leftJoin('orders', 'purchases.order_id', '=', 'orders.id')
            ->leftJoin('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereRaw('orders.created_at IS NULL OR (orders.created_at BETWEEN ? AND ? AND statuses.is_closing = ?)', 
                    [$startDate, $endDate.' 23:59:59', true]);
            })
            ->groupBy('products.id')
            ->havingRaw('products.quantity < 5 OR (products.quantity < 10 AND usage_frequency > 5)') 
            ->orderBy('usage_frequency', 'desc') 
            ->orderBy('products.quantity') 
            ->get();

        // Количество товаров по категориям
        $productsByCategory = Product::select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function($item) {
                $item->category_name = $item->category ? $item->category->name : 'Без категории';
                return $item;
            });

        // Количество товаров по брендам
        $productsByBrand = Product::select('brand_id', DB::raw('count(*) as total'))
            ->groupBy('brand_id')
            ->with('brand')
            ->get()
            ->map(function($item) {
                $item->brand_name = $item->brand ? $item->brand->name : 'Без бренда';
                return $item;
            });

        return view('dashboard.statistics.products', compact(
            'startDate',
            'endDate',
            'topSellingProducts',
            'lowStockProducts',
            'productsByCategory',
            'productsByBrand'
        ));
    }

    /**
     * Финансовая статистика
     */
    public function finance(Request $request)
    {
        $this->authorize('viewFinance', 'Statistics');
        
        // Период по умолчанию - последние 30 дней
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Общий доход по дням
        $revenueByDate = Order::whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->select(DB::raw('DATE(orders.created_at) as date'), DB::raw('sum(orders.total) as total_revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Доход от услуг
        $serviceRevenue = ServiceEntry::whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                      ->whereHas('status', function($q) {
                          $q->where('is_closing', true);
                      });
            })
            ->sum(DB::raw('price * quantity'));

        // Доход от товаров
        $productRevenue = Purchase::whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
                      ->whereHas('status', function($q) {
                          $q->where('is_closing', true);
                      });
            })
            ->sum(DB::raw('price * quantity'));

        // Общий доход за период
        $totalRevenue = Order::whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->sum('orders.total');

        // Средний чек
        $averageOrderTotal = Order::whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->avg('orders.total') ?? 0;

        // Количество заказов
        $ordersCount = Order::whereBetween('orders.created_at', [$startDate, $endDate.' 23:59:59'])
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->where('statuses.is_closing', true)
            ->count();
            
        // Доход по категориям товаров для радарной диаграммы
        $revenueByProductCategory = Purchase::select(
                'categories.name as category_name', 
                DB::raw('SUM(purchases.price * purchases.quantity) as revenue'),
                DB::raw('SUM(purchases.quantity) as quantity')
            )
            ->join('products', 'purchases.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                      ->whereHas('status', function($q) {
                          $q->where('is_closing', true);
                      });
            })
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
            
        // Доход по типам услуг для радарной диаграммы
        $revenueByServiceType = ServiceEntry::select(
                'service_types.name as type_name', 
                DB::raw('SUM(service_entries.price * service_entries.quantity) as revenue'),
                DB::raw('SUM(service_entries.quantity) as quantity')
            )
            ->join('services', 'service_entries.service_id', '=', 'services.id')
            ->join('service_types', 'services.service_type_id', '=', 'service_types.id')
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])
                      ->whereHas('status', function($q) {
                          $q->where('is_closing', true);
                      });
            })
            ->groupBy('service_types.id', 'service_types.name')
            ->orderBy('revenue', 'desc')
            ->get();

        return view('dashboard.statistics.finance', compact(
            'startDate',
            'endDate',
            'revenueByDate',
            'serviceRevenue',
            'productRevenue',
            'totalRevenue',
            'averageOrderTotal',
            'ordersCount',
            'revenueByProductCategory',
            'revenueByServiceType'
        ));
    }
} 