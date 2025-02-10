<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/filters', \App\Http\Controllers\Order\FilterListController::class);
        Route::get('/', \App\Http\Controllers\Order\IndexController::class);
        Route::post('/', \App\Http\Controllers\Order\StoreController::class);
        Route::get('/{order}', \App\Http\Controllers\Order\ShowController::class);
        Route::patch('/{order}', \App\Http\Controllers\Order\UpdateController::class);
        Route::delete('/{order}', \App\Http\Controllers\Order\DeleteController::class);
    });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/filters', \App\Http\Controllers\Customer\FilterListController::class);
        Route::get('/', \App\Http\Controllers\Customer\IndexController::class);
        Route::post('/', \App\Http\Controllers\Customer\StoreController::class);
        Route::get('/{customer}', \App\Http\Controllers\Customer\ShowController::class);
        Route::patch('/{customer}', \App\Http\Controllers\Customer\UpdateController::class);
        Route::delete('/{customer}', \App\Http\Controllers\Customer\DeleteController::class);
    });

    Route::group(['prefix' => 'accounting'], function () {
//        Route::get('/filters', \App\Http\Controllers\DayLedger\FilterListController::class);
        Route::get('/', \App\Http\Controllers\DayLedger\IndexController::class);
        Route::post('/', \App\Http\Controllers\DayLedger\StoreController::class);
        Route::get('/{day_ledger}', \App\Http\Controllers\DayLedger\ShowController::class);
        Route::patch('/{day_ledger}', \App\Http\Controllers\DayLedger\UpdateController::class);
        Route::delete('/{day_ledger}', \App\Http\Controllers\DayLedger\DeleteController::class);
    });

// });
