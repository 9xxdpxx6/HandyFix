<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список ролей
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.roles');
    }

    /**
     * Определяет, может ли пользователь просматривать роль
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('read.roles');
    }

    /**
     * Определяет, может ли пользователь создавать роли
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.roles');
    }

    /**
     * Определяет, может ли пользователь обновлять роль
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('update.roles');
    }

    /**
     * Определяет, может ли пользователь удалять роль
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('delete.roles');
    }
} 