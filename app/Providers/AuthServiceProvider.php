<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Employee;
use App\Models\LoyaltyLevel;
use App\Models\Qualification;
use App\Models\Role;
use App\Models\ServiceType;
use App\Models\Specialization;
use App\Models\Status;
use App\Models\VehicleModel;
use App\Policies\OrderPolicy;
use App\Policies\VehiclePolicy;
use App\Policies\ProductPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ServicePolicy;
use App\Policies\BrandPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\IconPolicy;
use App\Policies\LoyaltyLevelPolicy;
use App\Policies\QualificationPolicy;
use App\Policies\RolePolicy;
use App\Policies\ServiceTypePolicy;
use App\Policies\SpecializationPolicy;
use App\Policies\StatisticsPolicy;
use App\Policies\StatusPolicy;
use App\Policies\VehicleModelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Vehicle::class => VehiclePolicy::class,
        Product::class => ProductPolicy::class,
        Customer::class => CustomerPolicy::class,
        Service::class => ServicePolicy::class,
        Brand::class => BrandPolicy::class,
        Category::class => CategoryPolicy::class,
        Employee::class => EmployeePolicy::class,
        'Icon' => IconPolicy::class,
        LoyaltyLevel::class => LoyaltyLevelPolicy::class,
        VehicleModel::class => VehicleModelPolicy::class,
        Qualification::class => QualificationPolicy::class,
        Role::class => RolePolicy::class,
        ServiceType::class => ServiceTypePolicy::class,
        Specialization::class => SpecializationPolicy::class,
        Status::class => StatusPolicy::class,
        'Statistics' => StatisticsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        
        // Определяем глобальное Gate для проверки доступа к модулям
        Gate::define('access-module', function ($user, $module) {
            return $user->hasPermissionTo("read.{$module}");
        });
        
        // Определяем Gate для проверки админских прав
        Gate::define('admin-access', function ($user) {
            return $user->hasRole('admin');
        });

        // Определяем, кто имеет доступ к админ-панели
        Gate::define('access-admin-panel', function ($user) {
            return !$user->hasRole('client');
        });
        
        // Определяем, кто имеет доступ к клиентской части
        Gate::define('access-client-area', function ($user) {
            return true; // Все пользователи имеют доступ к клиентской части
        });
    }
}
