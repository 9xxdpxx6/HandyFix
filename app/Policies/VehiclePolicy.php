<?php

namespace App\Policies;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список автомобилей
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.vehicles');
    }

    /**
     * Определяет, может ли пользователь просматривать конкретный автомобиль
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        // Базовое разрешение на просмотр автомобилей
        if (!$user->hasPermissionTo('read.vehicles')) {
            return false;
        }
        
        // Клиенты могут видеть только свои автомобили
        if ($user->hasRole('client') && $vehicle->customer && $vehicle->customer->user_id !== $user->id) {
            return false;
        }
        
        return true;
    }

    /**
     * Определяет, может ли пользователь создавать автомобили
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.vehicles');
    }

    /**
     * Определяет, может ли пользователь обновлять автомобиль
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        // Базовая проверка права
        if (!$user->hasPermissionTo('update.vehicles')) {
            return false;
        }
        
        // Клиенты могут обновлять только свои автомобили
        if ($user->hasRole('client') && $vehicle->customer && $vehicle->customer->user_id !== $user->id) {
            return false;
        }
        
        return true;
    }

    /**
     * Определяет, может ли пользователь удалять автомобиль
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        // Базовая проверка
        if (!$user->hasPermissionTo('delete.vehicles')) {
            return false;
        }
        
        // Клиенты не могут удалять автомобили
        if ($user->hasRole('client')) {
            return false;
        }
        
        return true;
    }
} 