<?php

namespace App\Policies;

use App\Models\LoyaltyLevel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoyaltyLevelPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список уровней лояльности
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.loyalty');
    }

    /**
     * Определяет, может ли пользователь просматривать уровень лояльности
     */
    public function view(User $user, LoyaltyLevel $loyaltyLevel): bool
    {
        return $user->hasPermissionTo('read.loyalty');
    }

    /**
     * Определяет, может ли пользователь создавать уровни лояльности
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.loyalty');
    }

    /**
     * Определяет, может ли пользователь обновлять уровень лояльности
     */
    public function update(User $user, LoyaltyLevel $loyaltyLevel): bool
    {
        return $user->hasPermissionTo('update.loyalty');
    }

    /**
     * Определяет, может ли пользователь удалять уровень лояльности
     */
    public function delete(User $user, LoyaltyLevel $loyaltyLevel): bool
    {
        return $user->hasPermissionTo('delete.loyalty');
    }
} 