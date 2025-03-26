<?php

namespace App\Policies;

use App\Models\User;
use App\Services\IconService;
use Illuminate\Auth\Access\HandlesAuthorization;

class IconPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список иконок
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.icons');
    }

    /**
     * Определяет, может ли пользователь просматривать иконку
     */
    public function view(User $user, string $name): bool
    {
        return $user->hasPermissionTo('read.icons');
    }

    /**
     * Определяет, может ли пользователь создавать иконки
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.icons');
    }

    /**
     * Определяет, может ли пользователь обновлять иконку
     */
    public function update(User $user, string $name): bool
    {
        return $user->hasPermissionTo('update.icons');
    }

    /**
     * Определяет, может ли пользователь удалять иконку
     */
    public function delete(User $user, string $name): bool
    {
        return $user->hasPermissionTo('delete.icons');
    }
} 