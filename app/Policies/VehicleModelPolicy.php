<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehicleModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehicleModelPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список моделей автомобилей
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.models');
    }

    /**
     * Определяет, может ли пользователь просматривать модель автомобиля
     */
    public function view(User $user, VehicleModel $model): bool
    {
        return $user->hasPermissionTo('read.models');
    }

    /**
     * Определяет, может ли пользователь создавать модели автомобилей
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.models');
    }

    /**
     * Определяет, может ли пользователь обновлять модель автомобиля
     */
    public function update(User $user, VehicleModel $model): bool
    {
        return $user->hasPermissionTo('update.models');
    }

    /**
     * Определяет, может ли пользователь удалять модель автомобиля
     */
    public function delete(User $user, VehicleModel $model): bool
    {
        return $user->hasPermissionTo('delete.models');
    }
} 