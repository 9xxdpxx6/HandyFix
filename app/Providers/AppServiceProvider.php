<?php

namespace App\Providers;

use App\Http\ViewComposers\IconComposer;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');
        View::composer('components.icon-picker', IconComposer::class);
        
        // Регистрация наблюдателя для модели Order
        Order::observe(OrderObserver::class);
    }
}
