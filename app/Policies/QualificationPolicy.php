<?php

namespace App\Policies;

use App\Models\Qualification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QualificationPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список квалификаций
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.qualifications');
    }

    /**
     * Определяет, может ли пользователь просматривать квалификацию
     */
    public function view(User $user, Qualification $qualification): bool
    {
        return $user->hasPermissionTo('read.qualifications');
    }

    /**
     * Определяет, может ли пользователь создавать квалификации
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.qualifications');
    }

    /**
     * Определяет, может ли пользователь обновлять квалификацию
     */
    public function update(User $user, Qualification $qualification): bool
    {
        return $user->hasPermissionTo('update.qualifications');
    }

    /**
     * Определяет, может ли пользователь удалять квалификацию
     */
    public function delete(User $user, Qualification $qualification): bool
    {
        return $user->hasPermissionTo('delete.qualifications');
    }
} 