<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список товаров
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.products');
    }

    /**
     * Определяет, может ли пользователь просматривать товар
     */
    public function view(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('read.products');
    }

    /**
     * Определяет, может ли пользователь создавать товары
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.products');
    }

    /**
     * Определяет, может ли пользователь обновлять товар
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('update.products');
    }

    /**
     * Определяет, может ли пользователь удалять товар
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasPermissionTo('delete.products');
    }
} 