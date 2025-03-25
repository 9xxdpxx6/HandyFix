<?php

namespace App\Providers;

use App\Http\ViewComposers\IconComposer;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

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

        // Spatie Permission использует встроенные директивы Blade
        // Эти директивы доступны автоматически после установки пакета
        
        // Дополнительные директивы, если потребуется:
        Blade::directive('modulepermission', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermissionTo('read.{$expression}')): ?>";
        });
        
        Blade::directive('endmodulepermission', function () {
            return "<?php endif; ?>";
        });
        
        // Регистрация компонента Permission
        Blade::component('permission', \App\View\Components\Permission::class);
    }
}
