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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    Route::resources([
        'brands' => BrandController::class,
        'categories' => CategoryController::class,
        'customers' => CustomerController::class,
        'employees' => EmployeeController::class,
        'icons' => IconController::class,
        'loyalty-levels' => LoyaltyLevelController::class,
        'models' => ModelController::class,
        'orders' => OrderController::class,
        'products' => ProductController::class,
        'qualifications' => QualificationController::class,
        'roles' => RoleController::class,
        'services' => ServiceController::class,
        'service-types' => ServiceTypeController::class,
        'specializations' => SpecializationController::class,
        'statuses' => StatusController::class,
        'vehicles' => VehicleController::class,
    ]);
    
    // Маршруты для статистики
    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('/orders', [StatisticsController::class, 'orders'])->name('orders');
        Route::get('/vehicles', [StatisticsController::class, 'vehicles'])->name('vehicles');
        Route::get('/customers', [StatisticsController::class, 'customers'])->name('customers');
        Route::get('/employees', [StatisticsController::class, 'employees'])->name('employees');
        Route::get('/products', [StatisticsController::class, 'products'])->name('products');
        Route::get('/finance', [StatisticsController::class, 'finance'])->name('finance');
    });
});

// Маршруты аутентификации
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


