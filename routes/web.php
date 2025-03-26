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



Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
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
    });
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


