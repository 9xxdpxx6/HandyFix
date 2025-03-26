<?php

namespace App\Policies;

use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTypePolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список типов услуг
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.service-types');
    }

    /**
     * Определяет, может ли пользователь просматривать тип услуги
     */
    public function view(User $user, ServiceType $serviceType): bool
    {
        return $user->hasPermissionTo('read.service-types');
    }

    /**
     * Определяет, может ли пользователь создавать типы услуг
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.service-types');
    }

    /**
     * Определяет, может ли пользователь обновлять тип услуги
     */
    public function update(User $user, ServiceType $serviceType): bool
    {
        return $user->hasPermissionTo('update.service-types');
    }

    /**
     * Определяет, может ли пользователь удалять тип услуги
     */
    public function delete(User $user, ServiceType $serviceType): bool
    {
        return $user->hasPermissionTo('delete.service-types');
    }
} 