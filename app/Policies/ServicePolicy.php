<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список услуг
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.services');
    }

    /**
     * Определяет, может ли пользователь просматривать услугу
     */
    public function view(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('read.services');
    }

    /**
     * Определяет, может ли пользователь создавать услуги
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.services');
    }

    /**
     * Определяет, может ли пользователь обновлять услугу
     */
    public function update(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('update.services');
    }

    /**
     * Определяет, может ли пользователь удалять услугу
     */
    public function delete(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('delete.services');
    }
} 