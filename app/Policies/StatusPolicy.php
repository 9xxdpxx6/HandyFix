<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список статусов
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.statuses');
    }

    /**
     * Определяет, может ли пользователь просматривать статус
     */
    public function view(User $user, Status $status): bool
    {
        return $user->hasPermissionTo('read.statuses');
    }

    /**
     * Определяет, может ли пользователь создавать статусы
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.statuses');
    }

    /**
     * Определяет, может ли пользователь обновлять статус
     */
    public function update(User $user, Status $status): bool
    {
        return $user->hasPermissionTo('update.statuses');
    }

    /**
     * Определяет, может ли пользователь удалять статус
     */
    public function delete(User $user, Status $status): bool
    {
        return $user->hasPermissionTo('delete.statuses');
    }
} 