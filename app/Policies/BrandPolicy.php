<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список брендов
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.brands');
    }

    /**
     * Определяет, может ли пользователь просматривать бренд
     */
    public function view(User $user, Brand $brand): bool
    {
        return $user->hasPermissionTo('read.brands');
    }

    /**
     * Определяет, может ли пользователь создавать бренды
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.brands');
    }

    /**
     * Определяет, может ли пользователь обновлять бренд
     */
    public function update(User $user, Brand $brand): bool
    {
        return $user->hasPermissionTo('update.brands');
    }

    /**
     * Определяет, может ли пользователь удалять бренд
     */
    public function delete(User $user, Brand $brand): bool
    {
        return $user->hasPermissionTo('delete.brands');
    }
} 