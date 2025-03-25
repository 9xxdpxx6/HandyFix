<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список заказов
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.orders');
    }

    /**
     * Определяет, может ли пользователь просматривать конкретный заказ
     */
    public function view(User $user, Order $order): bool
    {
        // Проверяем доступ к заказам в целом
        if (!$user->hasPermissionTo('read.orders')) {
            return false;
        }
        
        // Если пользователь клиент, проверяем принадлежность заказа
        if ($user->hasRole('client') && $order->customer && $order->customer->user_id !== $user->id) {
            return false;
        }
        
        return true;
    }

    /**
     * Определяет, может ли пользователь создавать заказы
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.orders');
    }

    /**
     * Определяет, может ли пользователь обновлять заказ
     */
    public function update(User $user, Order $order): bool
    {
        // Базовая проверка права на обновление заказов
        if (!$user->hasPermissionTo('update.orders')) {
            return false;
        }
        
        // Если пользователь клиент, проверяем принадлежность заказа
        if ($user->hasRole('client') && $order->customer && $order->customer->user_id !== $user->id) {
            return false;
        }
        
        return true;
    }

    /**
     * Определяет, может ли пользователь удалять заказ
     */
    public function delete(User $user, Order $order): bool
    {
        // Только пользователи с правом удаления заказов
        if (!$user->hasPermissionTo('delete.orders')) {
            return false;
        }
        
        // Клиенты не могут удалять заказы
        if ($user->hasRole('client')) {
            return false;
        }
        
        return true;
    }
} 