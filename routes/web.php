<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\IconController;
use App\Http\Controllers\Dashboard\LoyaltyLevelController;
use App\Http\Controllers\Dashboard\ModelController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\QualificationController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\ServiceTypeController;
use App\Http\Controllers\Dashboard\SpecializationController;
use App\Http\Controllers\Dashboard\StatisticsController;
use App\Http\Controllers\Dashboard\StatusController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ServiceController as ClientServiceController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\VehicleController as ClientVehicleController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Маршруты логина/логаута для клиентской части
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Административная панель
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    // Маршруты авторизации для админки
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Защищенные маршруты администрации
    Route::middleware(['auth', 'admin.access'])->group(function () {
        Route::get('/home', [DashboardController::class, 'index'])->name('home');
        Route::get('/spravka', [DashboardController::class, 'guide'])->name('guide');
        
        Route::resource('brands', BrandController::class)
            ->middleware(['permission:read.brands']);
        
        Route::resource('categories', CategoryController::class)
            ->middleware(['permission:read.categories']);
        
        Route::resource('customers', CustomerController::class)
            ->middleware(['permission:read.customers']);
        
        Route::resource('employees', EmployeeController::class)
            ->middleware(['permission:read.employees']);
        
        Route::resource('icons', IconController::class);
        
        Route::resource('loyalty-levels', LoyaltyLevelController::class)
            ->middleware(['permission:read.loyalty']);
        
        Route::resource('models', ModelController::class)
            ->middleware(['permission:read.models']);
        
        Route::resource('orders', OrderController::class)
            ->middleware(['permission:read.orders']);
        
        Route::get('orders/{order}/print', [OrderController::class, 'print'])
            ->name('orders.print')
            ->middleware(['permission:read.orders']);
        
        Route::resource('products', ProductController::class)
            ->middleware(['permission:read.products']);
        
        Route::resource('qualifications', QualificationController::class)
            ->middleware(['permission:read.qualifications']);
        
        Route::resource('roles', RoleController::class)
            ->middleware(['role:admin']);
        
        Route::resource('services', ServiceController::class)
            ->middleware(['permission:read.services']);
        
        Route::resource('service-types', ServiceTypeController::class)
            ->middleware(['permission:read.service-types']);
        
        Route::resource('specializations', SpecializationController::class)
            ->middleware(['permission:read.specializations']);
        
        Route::resource('statuses', StatusController::class)
            ->middleware(['permission:read.statuses']);
        
        Route::resource('vehicles', VehicleController::class)
            ->middleware(['permission:read.vehicles']);
        
        Route::prefix('statistics')->name('statistics.')->middleware(['permission:read.statistics'])->group(function () {
            Route::get('/orders', [StatisticsController::class, 'orders'])->name('orders');
            Route::get('/vehicles', [StatisticsController::class, 'vehicles'])->name('vehicles');
            Route::get('/customers', [StatisticsController::class, 'customers'])->name('customers');
            Route::get('/employees', [StatisticsController::class, 'employees'])->name('employees');
            Route::get('/products', [StatisticsController::class, 'products'])->name('products');
            Route::get('/finance', [StatisticsController::class, 'finance'])->name('finance');
            Route::post('/export/{page}/{report}', [StatisticsController::class, 'export'])->name('export');
        });
    });
});

// Клиентский сайт
Route::get('/', [HomeController::class, 'index'])->name('home');

// Услуги
Route::get('/services', [ClientServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ClientServiceController::class, 'show'])->name('services.show');

// Регистрация
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Маршруты для авторизованных пользователей
Route::middleware(['auth'])->group(function () {
    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Управление автомобилями
    Route::resource('vehicles', ClientVehicleController::class)->except(['show']);
    
    // Заказы
    Route::get('/order/create', [ClientOrderController::class, 'create'])->name('order.create');
    Route::post('/order', [ClientOrderController::class, 'store'])->name('order.store');
    Route::get('/order/{order}', [ClientOrderController::class, 'show'])->name('order.show');
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('order.index');
    
    // Оплата заказа через СБП
    Route::get('/order/{order}/payment', [ClientOrderController::class, 'payment'])->name('order.payment');
    Route::post('/order/{order}/pay', [ClientOrderController::class, 'pay'])->name('order.pay');
    Route::get('/order/{order}/payment/callback', [ClientOrderController::class, 'paymentCallback'])->name('order.payment.callback');
});
