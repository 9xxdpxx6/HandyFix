<?php

namespace App\Policies;

use App\Models\Specialization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecializationPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список специализаций
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.specializations');
    }

    /**
     * Определяет, может ли пользователь просматривать специализацию
     */
    public function view(User $user, Specialization $specialization): bool
    {
        return $user->hasPermissionTo('read.specializations');
    }

    /**
     * Определяет, может ли пользователь создавать специализации
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.specializations');
    }

    /**
     * Определяет, может ли пользователь обновлять специализацию
     */
    public function update(User $user, Specialization $specialization): bool
    {
        return $user->hasPermissionTo('update.specializations');
    }

    /**
     * Определяет, может ли пользователь удалять специализацию
     */
    public function delete(User $user, Specialization $specialization): bool
    {
        return $user->hasPermissionTo('delete.specializations');
    }
} 