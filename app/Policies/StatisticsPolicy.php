<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatisticsPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать статистику заказов
     */
    public function viewOrders(User $user): bool
    {
        return $user->hasPermissionTo('read.statistics');
    }

    /**
     * Определяет, может ли пользователь просматривать статистику автомобилей
     */
    public function viewVehicles(User $user): bool
    {
        return $user->hasPermissionTo('read.statistics');
    }

    /**
     * Определяет, может ли пользователь просматривать статистику клиентов
     */
    public function viewCustomers(User $user): bool
    {
        return $user->hasPermissionTo('read.statistics');
    }

    /**
     * Определяет, может ли пользователь просматривать статистику сотрудников
     */
    public function viewEmployees(User $user): bool
    {
        return $user->hasPermissionTo('read.statistics');
    }

    /**
     * Определяет, может ли пользователь просматривать статистику товаров
     */
    public function viewProducts(User $user): bool
    {
        return $user->hasPermissionTo('read.statistics');
    }

    /**
     * Определяет, может ли пользователь просматривать финансовую статистику
     */
    public function viewFinance(User $user): bool
    {
        return $user->hasPermissionTo('read.statistics');
    }
} 