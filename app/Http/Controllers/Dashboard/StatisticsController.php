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
use App\Services\StatisticsExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Экспорт отчета статистики
     */
    public function export(Request $request, string $page, string $report)
    {
        try {
            $this->authorize('view' . ucfirst($page), 'Statistics');
            
            $format = $request->input('format', 'pdf');
            $exportService = new StatisticsExportService();
            
            // Получаем данные отчета
            $reportData = $this->getReportData($page, $report, $request);
            
            if (!$reportData) {
                return redirect()->back()->with('error', 'Отчет не найден: ' . $page . '/' . $report);
            }
            
            // Применяем экспорт в выбранном формате
            switch ($format) {
                case 'pdf':
                    return $exportService->exportToPdf(
                        $reportData['data'],
                        $reportData['title'],
                        $reportData['headers'] ?? [],
                        $reportData['filters'] ?? []
                    );
                case 'excel':
                    return $exportService->exportToExcel(
                        $reportData['data'],
                        $reportData['title'],
                        $reportData['headers'] ?? [],
                        $reportData['filters'] ?? []
                    );
                case 'word':
                    return $exportService->exportToWord(
                        $reportData['data'],
                        $reportData['title'],
                        $reportData['headers'] ?? [],
                        $reportData['filters'] ?? []
                    );
                default:
                    return redirect()->back()->with('error', 'Неверный формат экспорта: ' . $format);
            }
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage(), [
                'page' => $page,
                'report' => $report,
                'format' => $request->input('format'),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Ошибка экспорта: ' . $e->getMessage());
        }
    }

    /**
     * Получение данных отчета с применением фильтров
     */
    private function getReportData(string $page, string $report, Request $request): ?array
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Если есть даты, устанавливаем дефолтные значения
        if ($startDate) {
            $startDate = Carbon::parse($startDate)->format('Y-m-d');
        }
        if ($endDate) {
            $endDate = Carbon::parse($endDate)->format('Y-m-d') . ' 23:59:59';
        }

        $filters = [];
        if ($startDate) {
            $filters['Начальная дата'] = Carbon::parse($startDate)->format('d.m.Y');
        }
        if ($endDate) {
            $filters['Конечная дата'] = Carbon::parse($endDate)->format('d.m.Y');
        }

        switch ($page) {
            case 'orders':
                return $this->getOrdersReportData($report, $startDate, $endDate, $filters);
            case 'vehicles':
                return $this->getVehiclesReportData($report, $filters);
            case 'customers':
                return $this->getCustomersReportData($report, $startDate, $endDate, $filters);
            case 'employees':
                return $this->getEmployeesReportData($report, $startDate, $endDate, $filters);
            case 'products':
                return $this->getProductsReportData($report, $startDate, $endDate, $filters);
            case 'finance':
                return $this->getFinanceReportData($report, $startDate, $endDate, $filters);
            default:
                return null;
        }
    }

    /**
     * Данные отчетов по заказам
     */
    private function getOrdersReportData(string $report, ?string $startDate, ?string $endDate, array $filters): ?array
    {
        if (!$startDate) $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        if (!$endDate) $endDate = Carbon::now()->format('Y-m-d') . ' 23:59:59';

        switch ($report) {
            case 'orders_by_status':
                $data = Order::whereBetween('created_at', [$startDate, $endDate])
                    ->select('status_id', DB::raw('count(*) as total'))
                    ->groupBy('status_id')
                    ->get()
                    ->map(function($item) {
                        $status = Status::find($item->status_id);
                        return [
                            'Статус' => $status ? $status->name : 'Нет статуса',
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Заказы по статусам',
                    'headers' => ['Статус', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'orders_by_date':
                $data = Order::whereBetween('orders.created_at', [$startDate, $endDate])
                    ->join('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->select(DB::raw('DATE(orders.created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Дата' => Carbon::parse($item->date)->format('d.m.Y'),
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Динамика заказов',
                    'headers' => ['Дата', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_services':
                $data = ServiceEntry::whereHas('order', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate])
                              ->whereHas('status', function($q) {
                                  $q->where('is_closing', true);
                              });
                    })
                    ->select('service_name', DB::raw('sum(quantity) as total'))
                    ->groupBy('service_name')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($item) {
                        return [
                            'Услуга' => $item->service_name,
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Топ 5 услуг',
                    'headers' => ['Услуга', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_products':
                $data = Purchase::whereHas('order', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate])
                              ->whereHas('status', function($q) {
                                  $q->where('is_closing', true);
                              });
                    })
                    ->select('product_name', DB::raw('sum(quantity) as total'))
                    ->groupBy('product_name')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get()
                    ->map(function($item) {
                        return [
                            'Товар' => $item->product_name,
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Топ 5 товаров',
                    'headers' => ['Товар', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];
        }
        return null;
    }

    /**
     * Данные отчетов по автомобилям
     */
    private function getVehiclesReportData(string $report, array $filters): ?array
    {
        switch ($report) {
            case 'vehicles_by_brand':
                $data = Vehicle::select('model_id', DB::raw('count(*) as total'))
                    ->groupBy('model_id')
                    ->with('model.brand')
                    ->get()
                    ->groupBy(function($item) {
                        return $item->model->brand->name ?? 'Нет бренда';
                    })
                    ->map(function($items) {
                        return $items->sum('total');
                    })
                    ->sortDesc()
                    ->map(function($count, $brand) {
                        return [
                            'Бренд' => $brand,
                            'Количество' => $count
                        ];
                    })->values()->toArray();
                return [
                    'title' => 'Распределение по брендам',
                    'headers' => ['Бренд', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'vehicles_by_year':
                $data = Vehicle::select('year', DB::raw('count(*) as total'))
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Год' => $item->year,
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Распределение по годам выпуска',
                    'headers' => ['Год', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_services_by_brand':
                $vehiclesByBrand = Vehicle::select('model_id', DB::raw('count(*) as total'))
                    ->groupBy('model_id')
                    ->with('model.brand')
                    ->get()
                    ->groupBy(function($item) {
                        return $item->model->brand->name ?? 'Нет бренда';
                    })
                    ->keys();
                
                $data = [];
                foreach ($vehiclesByBrand as $brand) {
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
                    
                    foreach ($services as $service) {
                        $data[] = [
                            'Бренд' => $brand,
                            'Услуга' => $service->service_name,
                            'Количество' => $service->total
                        ];
                    }
                }
                return [
                    'title' => 'Топовые услуги по брендам',
                    'headers' => ['Бренд', 'Услуга', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];
        }
        return null;
    }

    /**
     * Данные отчетов по клиентам
     */
    private function getCustomersReportData(string $report, ?string $startDate, ?string $endDate, array $filters): ?array
    {
        if (!$startDate) $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        if (!$endDate) $endDate = Carbon::now()->format('Y-m-d') . ' 23:59:59';

        switch ($report) {
            case 'new_customers_by_date':
                $data = Customer::whereBetween('created_at', [$startDate, $endDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Дата' => Carbon::parse($item->date)->format('d.m.Y'),
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Новые клиенты',
                    'headers' => ['Дата', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'customers_by_loyalty':
                $data = Customer::select('loyalty_level_id', DB::raw('count(*) as total'))
                    ->groupBy('loyalty_level_id')
                    ->with('loyaltyLevel')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Уровень лояльности' => $item->loyaltyLevel ? $item->loyaltyLevel->name : 'Без уровня',
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Клиенты по уровням лояльности',
                    'headers' => ['Уровень лояльности', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_customers_by_orders':
                $data = Customer::withCount(['orders' => function($query) {
                        $query->join('statuses', 'orders.status_id', '=', 'statuses.id')
                              ->where('statuses.is_closing', true);
                    }])
                    ->orderBy('orders_count', 'desc')
                    ->limit(10)
                    ->with('user')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Клиент' => $item->user->name ?? 'Не указано',
                            'Заказов' => $item->orders_count
                        ];
                    })->toArray();
                return [
                    'title' => 'Топ 10 клиентов по количеству заказов',
                    'headers' => ['Клиент', 'Заказов'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_customers_by_total':
                $data = Customer::with('user')
                    ->select('customers.id', 'customers.user_id', DB::raw('SUM(orders.total) as total_spent'))
                    ->join('orders', 'customers.id', '=', 'orders.customer_id')
                    ->join('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->groupBy('customers.id', 'customers.user_id')
                    ->orderBy('total_spent', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        return [
                            'Клиент' => $item->user->name ?? 'Не указано',
                            'Сумма' => number_format($item->total_spent, 2, ',', ' ') . ' ₽'
                        ];
                    })->toArray();
                return [
                    'title' => 'Топ 10 клиентов по сумме заказов',
                    'headers' => ['Клиент', 'Сумма'],
                    'data' => $data,
                    'filters' => $filters
                ];
        }
        return null;
    }

    /**
     * Данные отчетов по сотрудникам
     */
    private function getEmployeesReportData(string $report, ?string $startDate, ?string $endDate, array $filters): ?array
    {
        if (!$startDate) $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        if (!$endDate) $endDate = Carbon::now()->format('Y-m-d') . ' 23:59:59';

        switch ($report) {
            case 'employees_by_specialization':
                $data = Employee::select('specialization_id', DB::raw('count(*) as total'))
                    ->groupBy('specialization_id')
                    ->with('specialization')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Специализация' => $item->specialization ? $item->specialization->name : 'Без специализации',
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Сотрудники по специализациям',
                    'headers' => ['Специализация', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'employees_by_seniority':
                $data = Employee::select(DB::raw('FLOOR(seniority) as seniority_years'), DB::raw('count(*) as total'))
                    ->groupBy('seniority_years')
                    ->orderBy('seniority_years')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Стаж (лет)' => $item->seniority_years,
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Сотрудники по стажу',
                    'headers' => ['Стаж (лет)', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_mechanics':
                $data = Employee::with(['user', 'specialization', 'qualification'])
                    ->select('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id', DB::raw('COUNT(service_entries.id) as services_count'))
                    ->leftJoin('service_entries', 'employees.id', '=', 'service_entries.mechanic_id')
                    ->leftJoin('orders', 'service_entries.order_id', '=', 'orders.id')
                    ->leftJoin('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->groupBy('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id')
                    ->orderBy('services_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        return [
                            'Механик' => $item->user->name ?? 'Не указано',
                            'Специализация' => $item->specialization->code ?? '-',
                            'Квалификация' => $item->qualification->code ?? '-',
                            'Кол-во услуг' => $item->services_count
                        ];
                    })->toArray();
                return [
                    'title' => 'Топ 10 механиков по количеству выполненных работ',
                    'headers' => ['Механик', 'Специализация', 'Квалификация', 'Кол-во услуг'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_managers':
                $data = Employee::with(['user', 'specialization', 'qualification'])
                    ->select('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id', DB::raw('COUNT(orders.id) as orders_count'))
                    ->leftJoin('orders', 'employees.id', '=', 'orders.manager_id')
                    ->leftJoin('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->groupBy('employees.id', 'employees.user_id', 'employees.specialization_id', 'employees.qualification_id')
                    ->orderBy('orders_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        return [
                            'Менеджер' => $item->user->name ?? 'Не указано',
                            'Специализация' => $item->specialization->code ?? '-',
                            'Квалификация' => $item->qualification->code ?? '-',
                            'Кол-во заказов' => $item->orders_count
                        ];
                    })->toArray();
                return [
                    'title' => 'Топ 10 менеджеров по количеству заказов',
                    'headers' => ['Менеджер', 'Специализация', 'Квалификация', 'Кол-во заказов'],
                    'data' => $data,
                    'filters' => $filters
                ];
        }
        return null;
    }

    /**
     * Данные отчетов по товарам
     */
    private function getProductsReportData(string $report, ?string $startDate, ?string $endDate, array $filters): ?array
    {
        if (!$startDate) $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        if (!$endDate) $endDate = Carbon::now()->format('Y-m-d') . ' 23:59:59';

        switch ($report) {
            case 'products_by_category':
                $data = Product::select('category_id', DB::raw('count(*) as total'))
                    ->groupBy('category_id')
                    ->with('category')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Категория' => $item->category ? $item->category->name : 'Без категории',
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Товары по категориям',
                    'headers' => ['Категория', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'products_by_brand':
                $data = Product::select('brand_id', DB::raw('count(*) as total'))
                    ->groupBy('brand_id')
                    ->with('brand')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Бренд' => $item->brand ? $item->brand->name : 'Без бренда',
                            'Количество' => $item->total
                        ];
                    })->toArray();
                return [
                    'title' => 'Товары по брендам',
                    'headers' => ['Бренд', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'top_selling_products':
                $data = Purchase::with('product')
                    ->select('product_id', DB::raw('sum(quantity) as total_quantity'))
                    ->whereHas('order', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate])
                              ->whereHas('status', function($q) {
                                  $q->where('is_closing', true);
                              });
                    })
                    ->groupBy('product_id')
                    ->orderBy('total_quantity', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        return [
                            'Товар' => $item->product->name ?? 'Не указано',
                            'Продано (шт.)' => $item->total_quantity
                        ];
                    })->toArray();
                return [
                    'title' => 'Топ 10 самых продаваемых товаров',
                    'headers' => ['Товар', 'Продано (шт.)'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'low_stock_products':
                $data = Product::select('products.*', DB::raw('IFNULL(SUM(purchases.quantity), 0) as usage_frequency'))
                    ->leftJoin('purchases', 'products.id', '=', 'purchases.product_id')
                    ->leftJoin('orders', 'purchases.order_id', '=', 'orders.id')
                    ->leftJoin('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where(function($query) use ($startDate, $endDate) {
                        $query->whereRaw('orders.created_at IS NULL OR (orders.created_at BETWEEN ? AND ? AND statuses.is_closing = ?)', 
                            [$startDate, $endDate, true]);
                    })
                    ->groupBy('products.id')
                    ->havingRaw('products.quantity < 5 OR (products.quantity < 10 AND usage_frequency > 5)') 
                    ->orderBy('usage_frequency', 'desc') 
                    ->orderBy('products.quantity') 
                    ->get()
                    ->map(function($item) {
                        return [
                            'Товар' => $item->name,
                            'Остаток' => $item->quantity,
                            'Частота использования' => $item->usage_frequency
                        ];
                    })->toArray();
                return [
                    'title' => 'Товары с низким запасом',
                    'headers' => ['Товар', 'Остаток', 'Частота использования'],
                    'data' => $data,
                    'filters' => $filters
                ];
        }
        return null;
    }

    /**
     * Данные отчетов по финансам
     */
    private function getFinanceReportData(string $report, ?string $startDate, ?string $endDate, array $filters): ?array
    {
        if (!$startDate) $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        if (!$endDate) $endDate = Carbon::now()->format('Y-m-d') . ' 23:59:59';

        switch ($report) {
            case 'revenue_by_date':
                $data = Order::whereBetween('orders.created_at', [$startDate, $endDate])
                    ->join('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->select(DB::raw('DATE(orders.created_at) as date'), DB::raw('sum(orders.total) as total_revenue'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Дата' => Carbon::parse($item->date)->format('d.m.Y'),
                            'Доход (₽)' => number_format($item->total_revenue, 2, ',', ' ')
                        ];
                    })->toArray();
                return [
                    'title' => 'Динамика дохода',
                    'headers' => ['Дата', 'Доход (₽)'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'revenue_by_product_category':
                $data = Purchase::select(
                        'categories.name as category_name', 
                        DB::raw('SUM(purchases.price * purchases.quantity) as revenue'),
                        DB::raw('SUM(purchases.quantity) as quantity')
                    )
                    ->join('products', 'purchases.product_id', '=', 'products.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->whereHas('order', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate])
                              ->whereHas('status', function($q) {
                                  $q->where('is_closing', true);
                              });
                    })
                    ->groupBy('categories.id', 'categories.name')
                    ->orderBy('revenue', 'desc')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Категория' => $item->category_name,
                            'Доход (₽)' => number_format($item->revenue, 2, ',', ' '),
                            'Количество' => $item->quantity
                        ];
                    })->toArray();
                return [
                    'title' => 'Доход по категориям товаров',
                    'headers' => ['Категория', 'Доход (₽)', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'revenue_by_service_type':
                $data = ServiceEntry::select(
                        'service_types.name as type_name', 
                        DB::raw('SUM(service_entries.price * service_entries.quantity) as revenue'),
                        DB::raw('SUM(service_entries.quantity) as quantity')
                    )
                    ->join('services', 'service_entries.service_id', '=', 'services.id')
                    ->join('service_types', 'services.service_type_id', '=', 'service_types.id')
                    ->whereHas('order', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate])
                              ->whereHas('status', function($q) {
                                  $q->where('is_closing', true);
                              });
                    })
                    ->groupBy('service_types.id', 'service_types.name')
                    ->orderBy('revenue', 'desc')
                    ->get()
                    ->map(function($item) {
                        return [
                            'Тип услуги' => $item->type_name,
                            'Доход (₽)' => number_format($item->revenue, 2, ',', ' '),
                            'Количество' => $item->quantity
                        ];
                    })->toArray();
                return [
                    'title' => 'Доход по типам услуг',
                    'headers' => ['Тип услуги', 'Доход (₽)', 'Количество'],
                    'data' => $data,
                    'filters' => $filters
                ];

            case 'key_metrics':
                $totalRevenue = Order::whereBetween('orders.created_at', [$startDate, $endDate])
                    ->join('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->sum('orders.total');
                
                $serviceRevenue = ServiceEntry::whereHas('order', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate])
                              ->whereHas('status', function($q) {
                                  $q->where('is_closing', true);
                              });
                    })
                    ->sum(DB::raw('price * quantity'));
                
                $productRevenue = Purchase::whereHas('order', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('orders.created_at', [$startDate, $endDate])
                              ->whereHas('status', function($q) {
                                  $q->where('is_closing', true);
                              });
                    })
                    ->sum(DB::raw('price * quantity'));
                
                $ordersCount = Order::whereBetween('orders.created_at', [$startDate, $endDate])
                    ->join('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->count();
                
                $averageOrderTotal = $ordersCount > 0 ? $totalRevenue / $ordersCount : 0;
                
                $revenueByDate = Order::whereBetween('orders.created_at', [$startDate, $endDate])
                    ->join('statuses', 'orders.status_id', '=', 'statuses.id')
                    ->where('statuses.is_closing', true)
                    ->select(DB::raw('DATE(orders.created_at) as date'), DB::raw('sum(orders.total) as total_revenue'))
                    ->groupBy('date')
                    ->get();
                
                $daysCount = $revenueByDate->count();
                $revenuePerDay = $daysCount > 0 ? $totalRevenue / $daysCount : 0;
                $ordersPerDay = $daysCount > 0 ? $ordersCount / $daysCount : 0;
                
                $data = [
                    ['Показатель' => 'Общий доход', 'Значение' => number_format($totalRevenue, 2, ',', ' ') . ' ₽'],
                    ['Показатель' => 'Доход от услуг', 'Значение' => number_format($serviceRevenue, 2, ',', ' ') . ' ₽'],
                    ['Показатель' => 'Доход от товаров', 'Значение' => number_format($productRevenue, 2, ',', ' ') . ' ₽'],
                    ['Показатель' => 'Средний чек', 'Значение' => number_format($averageOrderTotal, 2, ',', ' ') . ' ₽'],
                    ['Показатель' => 'Количество заказов', 'Значение' => $ordersCount],
                    ['Показатель' => 'Доход на заказ', 'Значение' => number_format($ordersCount > 0 ? $totalRevenue / $ordersCount : 0, 2, ',', ' ') . ' ₽'],
                    ['Показатель' => 'Доход в день', 'Значение' => number_format($revenuePerDay, 2, ',', ' ') . ' ₽'],
                    ['Показатель' => 'Заказов в день', 'Значение' => number_format($ordersPerDay, 2, ',', ' ')],
                ];
                return [
                    'title' => 'Ключевые показатели',
                    'headers' => ['Показатель', 'Значение'],
                    'data' => $data,
                    'filters' => $filters
                ];
        }
        return null;
    }
} 