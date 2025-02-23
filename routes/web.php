<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

    Route::resource('orders', \App\Http\Controllers\Dashboard\OrderController::class)->names('dashboard.orders');
    Route::resource('customers', \App\Http\Controllers\Dashboard\CustomerController::class)->names('dashboard.customers');
    Route::resource('vehicles', \App\Http\Controllers\Dashboard\CustomerController::class)->names('dashboard.vehicles');
});

Auth::routes();

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


