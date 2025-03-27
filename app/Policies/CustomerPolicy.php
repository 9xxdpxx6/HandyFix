<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список клиентов
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.customers');
    }

    /**
     * Определяет, может ли пользователь просматривать клиента
     */
    public function view(User $user, Customer $customer): bool
    {
        // Базовая проверка права на просмотр клиентов
        if ($user->hasPermissionTo('read.customers')) {
            return true;
        }
        
        // Клиент может просматривать только свой профиль
        if ($user->hasRole('client') && $customer->user_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Определяет, может ли пользователь создавать клиентов
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.customers');
    }

    /**
     * Определяет, может ли пользователь обновлять клиента
     */
    public function update(User $user, Customer $customer): bool
    {
        // Базовая проверка права на обновление клиентов
        if ($user->hasPermissionTo('update.customers')) {
            return true;
        }
        
        // Клиент может обновлять только свой профиль
        if ($user->hasRole('client') && $customer->user_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Определяет, может ли пользователь удалять клиента
     */
    public function delete(User $user, Customer $customer): bool
    {
        // Только админ или модератор может удалять клиентов
        if (!$user->hasPermissionTo('delete.customers')) {
            return false;
        }
        
        // Клиент не может удалять аккаунты
        if ($user->hasRole('client')) {
            return false;
        }
        
        return true;
    }
} 