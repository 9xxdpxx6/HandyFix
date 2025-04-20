<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список категорий
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.categories');
    }

    /**
     * Определяет, может ли пользователь просматривать категорию
     */
    public function view(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('read.categories');
    }

    /**
     * Определяет, может ли пользователь создавать категории
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.categories');
    }

    /**
     * Определяет, может ли пользователь обновлять категорию
     */
    public function update(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('update.categories');
    }

    /**
     * Определяет, может ли пользователь удалять категорию
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('delete.categories');
    }
} 