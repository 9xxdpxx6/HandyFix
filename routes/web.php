<?php

use Illuminate\Support\Facades\Auth;
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
    return view('client.home');
})->name('home');

//Route::prefix('dashboard')->middleware(['auth'])->group(function () {
Route::prefix('dashboard')->group(function () {
    Route::get('/home', function () {
        return view('dashboard.home');
    })->name('dashboard.home');

    Route::resource('brands', \App\Http\Controllers\Dashboard\BrandController::class)->names('dashboard.brands');
    Route::resource('categories', \App\Http\Controllers\Dashboard\CategoryController::class)->names('dashboard.categories');
    Route::resource('customers', \App\Http\Controllers\Dashboard\CustomerController::class)->names('dashboard.customers');
    Route::resource('employees', \App\Http\Controllers\Dashboard\EmployeeController::class)->names('dashboard.employees');
    Route::resource('loyalty-levels', \App\Http\Controllers\Dashboard\LoyaltyLevelController::class)->names('dashboard.loyalty-levels');
    Route::resource('orders', \App\Http\Controllers\Dashboard\OrderController::class)->names('dashboard.orders');
    Route::resource('products', \App\Http\Controllers\Dashboard\ProductController::class)->names('dashboard.products');
    Route::resource('qualifications', \App\Http\Controllers\Dashboard\QualificationController::class)->names('dashboard.qualifications');
    Route::resource('services', \App\Http\Controllers\Dashboard\ServiceController::class)->names('dashboard.services');
    Route::resource('service-types', \App\Http\Controllers\Dashboard\ServiceTypeController::class)->names('dashboard.service-types');
    Route::resource('specializations', \App\Http\Controllers\Dashboard\SpecializationController::class)->names('dashboard.specializations');
    Route::resource('statuses', \App\Http\Controllers\Dashboard\StatusController::class)->names('dashboard.statuses');
    Route::resource('vehicles', \App\Http\Controllers\Dashboard\VehicleController::class)->names('dashboard.vehicles');
    Route::resource('icons', \App\Http\Controllers\Dashboard\IconController::class)->names('dashboard.icons');
});

Auth::routes();

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


